<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use App\Models\PipelineStage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Customer Details')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone_number')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(),
                Forms\Components\Section::make('Lead Details')
                    ->schema([
                        Forms\Components\Select::make('lead_source_id')
                            ->relationship('leadSource', 'name'),
                        Forms\Components\Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple(),
                        Forms\Components\Select::make('pipeline_stage_id')
                            ->relationship('pipelineStage', 'name', function ($query) {
                                $query->orderBy('position', 'asc');
                            })
                            ->default(PipelineStage::where('is_default', true)->first()?->id)
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Documents')
                    // This will make the section visible only on the edit page
                    ->visibleOn('edit')
                    ->schema([
                        Forms\Components\Repeater::make('documents')
                            ->relationship('documents')
                            ->hiddenLabel()
                            ->reorderable(false)
                            ->addActionLabel('Add Document')
                            ->schema([
                                Forms\Components\FileUpload::make('file_path')
                                    ->required(),
                                Forms\Components\Textarea::make('comments'),
                            ])
                            ->columns()
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Name')
                    ->formatStateUsing(function ($record) {
                        $tagsList = view('customer.tagsList', ['tags' => $record->tags])->render();

                        return $record->first_name . ' ' . $record->last_name . ' ' . $tagsList;
                    })
                    ->html()
                    ->searchable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('leadSource.name'),
                Tables\Columns\TextColumn::make('pipelineStage.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('Move to Stage')
                    ->hidden(fn ($record) => $record->trashed())
                    ->icon('heroicon-m-pencil-square')
                    ->form([
                        Forms\Components\Select::make('pipeline_stage_id')
                            ->label('Status')
                            ->options(PipelineStage::pluck('name', 'id')->toArray())
                            ->default(function (Customer $record) {
                                $currentPosition = $record->pipelineStage->position;
                                return PipelineStage::where('position', '>', $currentPosition)->first()?->id;
                            }),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                    ])
                    ->action(function (Customer $customer, array $data): void {
                        $customer->pipeline_stage_id = $data['pipeline_stage_id'];
                        $customer->save();

                        $customer->pipelineStageLogs()->create([
                            'pipeline_stage_id' => $data['pipeline_stage_id'],
                            'notes' => $data['notes'],
                            'user_id' => auth()->id()
                        ]);

                        Notification::make()
                            ->title('Customer Pipeline Updated')
                            ->success()
                            ->send();
                    }),
            ])
            ->recordUrl(function ($record) {
                // If the record is trashed, return null
                if ($record->trashed()) {
                    // Null will disable the row click
                    return null;
                }

                // Otherwise, return the edit page URL
                return Pages\ViewCustomer::getUrl([$record->id]);
            })
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        TextEntry::make('first_name'),
                        TextEntry::make('last_name'),
                    ])
                    ->columns(),
                Section::make('Contact Information')
                    ->schema([
                        TextEntry::make('email'),
                        TextEntry::make('phone_number'),
                    ])
                    ->columns(),
                Section::make('Additional Details')
                    ->schema([
                        TextEntry::make('description'),
                    ]),
                Section::make('Lead and Stage Information')
                    ->schema([
                        TextEntry::make('leadSource.name'),
                        TextEntry::make('pipelineStage.name'),
                    ])
                    ->columns(),
                Section::make('Documents')
                    // This will hide the section if there are no documents
                    ->hidden(fn ($record) => $record->documents->isEmpty())
                    ->schema([
                        RepeatableEntry::make('documents')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('file_path')
                                    ->label('Document')
                                    // This will rename the column to "Download Document" (otherwise, it's just the file name)
                                    ->formatStateUsing(fn () => "Download Document")
                                    // URL to be used for the download (link), and the second parameter is for the new tab
                                    ->url(fn ($record) => Storage::url($record->file_path), true)
                                    // This will make the link look like a "badge" (blue)
                                    ->badge()
                                    ->color(Color::Blue),
                                TextEntry::make('comments'),
                            ])
                            ->columns()
                    ]),
                Section::make('Pipeline Stage History and Notes')
                    ->schema([
                        ViewEntry::make('pipelineStageLogs')
                            ->label('')
                            ->view('infolists.components.pipeline-stage-history-list')
                    ])
                    ->collapsible()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
