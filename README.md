<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel CRM with filament project

## Step by step

1. **Install Laravel and Filament**

     -  Laravel Installation: ```create-project laravel/laravel laravel-crm-filament```
     - Filament Installation
        ```
            composer require filament/filament
            php artisan filament:install --panels
            php artisan make:filament-user
        ```
     - Logging Into Filament
2. **Creating Lead Sources Resource**
    - Create lead_sources DB structure: Model/Migration and a belongsTo relationship with customers
    - Create Seeds with semi-real data without factories
    - Create a Filament Resource for Lead sources
    - Add a DeleteAction to the table with validation if that record is used
    - Add lead source information to the Customer Resource table/form
    - Divide the menu into two levels: introduce Settings parent menu item
3. **Creating Customers Resource**
    - Create DB structure for Customers: Model/Migration
    - Create Factories/Seeds for testing data
    - Generate Filament Resource directly from the DB structure
    - Hide the deleted_at column from the table
    - Merge first_name and last_name into one table column
4. **Creating Tags for Customers**
    - Create tags DB structure: Model/Migration and a belongsToMany relationship with customers
    - Create Seeds with semi-real data without factories
    - Create a Filament Resource for Tags
    - Add a ColorPicker field to the form and a ColorColumn column to the table
    - Add a DeleteAction to the table with validation if that record is used
    - Add tags to the Customer form with Select::make()->multiple()
    - Add tags to the Customer table in the same column of name using formatStateUsing() and rendering a separate Blade View
5. **Pipeline Stages Resource: Reorderable**
    - Create pipeline_stages DB structure: Model/Migration and a hasMany relationship to customers
    - Create Seeds with semi-real data without factories
    - Create a Filament Resource for Pipeline Stages
    - Auto-assign the new position to a new Pipeline Stage
    - Make the table reorderable with the position field
    - Add a Custom Action Set Default with confirmation
    - Add a DeleteAction to the table with validation if that record is used
    - Add pipeline stage information to the Customer Resource table/form
6. **Moving Customers through Pipeline Stages**
    - Create a CustomerPipelineStage Model to save the history of the Customer's Pipeline Stage changes and any comments added.
    - Add a custom Table Action to move customers to other pipeline stages.
    - Add creating and updating action Observers to our Customer Model to save the history.
7. **Customers by Stage: Tabs with Numbers**
    - Dynamically create tabs for each Pipeline Stage
    - Create a new tab called All to show all Customers
    - Add counters to each tab to show how many Customers are in each group
8. **SoftDeletes: Archive and Restore Customers**
    - Add the Archived tab to the Customers table
    - Add Delete button to the table
    - Add the Restore button to the Archived tab
    - Disable row click on the Archived tab
9. **Customer View Page with Infolist**
10. **Customer Documents: Upload/Download**
11. **Custom Fields for Customers**
