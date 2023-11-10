<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\PipelineStage;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];

        // Adding `all` as our first tab
        $tabs['all'] = Tab::make('All Customers')
            // We will add a badge to show how many customers are in this tab
            ->badge(Customer::count());

        if (!auth()->user()->isAdmin()) {
            $tabs['my'] = Tab::make('My Customers')
                ->badge(Customer::where('employee_id', auth()->id())->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('employee_id', auth()->id());
                });
        }
        
        // Load all Pipeline Stages
        $pipelineStages = PipelineStage::orderBy('position')->withCount('customers')->get();

        // Loop through each Pipeline Stage
        foreach ($pipelineStages as $pipelineStage) {
            // Add a tab for each Pipeline Stage
            // Array index is going to be used in the URL as a slug, so we transform the name into a slug
            $tabs[str($pipelineStage->name)->slug()->toString()] = Tab::make($pipelineStage->name)
                // We will add a badge to show how many customers are in this tab
                ->badge($pipelineStage->customers_count)
                // We will modify the query to only show customers in this Pipeline Stage
                ->modifyQueryUsing(function ($query) use ($pipelineStage) {
                    return $query->where('pipeline_stage_id', $pipelineStage->id);
                });
        }
        $tabs['archived'] = Tab::make('Archived')
            ->badge(Customer::onlyTrashed()->count())
            ->modifyQueryUsing(function ($query) {
                return $query->onlyTrashed();
            });

        return $tabs;
    }
}
