<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel CRM with filament project

## Step by step

1. **[Install Laravel and Filament](https://github.com/tqt97/laravel-crm-filament/commit/f23830243bacd4772de841d9d503e010ca311566)**

     -  Laravel Installation: ```create-project laravel/laravel laravel-crm-filament```
     - Filament Installation
        ```
            composer require filament/filament
            php artisan filament:install --panels
            php artisan make:filament-user
        ```
     - Logging Into Filament
2. **[Creating Lead Sources Resource](https://github.com/tqt97/laravel-crm-filament/commit/fc2622217ed54bf67d1775b41869820961345696)**
    - Create lead_sources DB structure: Model/Migration and a belongsTo relationship with customers
    - Create Seeds with semi-real data without factories
    - Create a Filament Resource for Lead sources
    - Add a DeleteAction to the table with validation if that record is used
    - Add lead source information to the Customer Resource table/form
    - Divide the menu into two levels: introduce Settings parent menu item
3. **[Creating Customers Resource](https://github.com/tqt97/laravel-crm-filament/commit/3784a997dd0388c3da9d78e2ab65a7252d30ef3a)**
    - Create DB structure for Customers: Model/Migration
    - Create Factories/Seeds for testing data
    - Generate Filament Resource directly from the DB structure
    - Hide the deleted_at column from the table
    - Merge first_name and last_name into one table column
4. **[Creating Tags for Customers](https://github.com/tqt97/laravel-crm-filament/commit/c22677088acbeb812ffd469118bdd63d25350e0e)**
    - Create tags DB structure: Model/Migration and a belongsToMany relationship with customers
    - Create Seeds with semi-real data without factories
    - Create a Filament Resource for Tags
    - Add a ColorPicker field to the form and a ColorColumn column to the table
    - Add a DeleteAction to the table with validation if that record is used
    - Add tags to the Customer form with Select::make()->multiple()
    - Add tags to the Customer table in the same column of name using formatStateUsing() and rendering a separate Blade View
5. **[Pipeline Stages Resource: Reorderable](https://github.com/tqt97/laravel-crm-filament/commit/d2ad40259aeb6ed6c9823f0610e00d4e0dc99df6)**
    - Create pipeline_stages DB structure: Model/Migration and a hasMany relationship to customers
    - Create Seeds with semi-real data without factories
    - Create a Filament Resource for Pipeline Stages
    - Auto-assign the new position to a new Pipeline Stage
    - Make the table reorderable with the position field
    - Add a Custom Action Set Default with confirmation
    - Add a DeleteAction to the table with validation if that record is used
    - Add pipeline stage information to the Customer Resource table/form
6. **[Moving Customers through Pipeline Stages](https://github.com/tqt97/laravel-crm-filament/commit/93410a572e3b6f630e1536e353a921ff1a6d651f)**
    - Create a CustomerPipelineStage Model to save the history of the Customer's Pipeline Stage changes and any comments added.
    - Add a custom Table Action to move customers to other pipeline stages.
    - Add creating and updating action Observers to our Customer Model to save the history.
7. **[Customers by Stage: Tabs with Numbers](https://github.com/tqt97/laravel-crm-filament/commit/69b0ebe6d3d79f2afdfb9ca98534f407a8869c48)**
    - Dynamically create tabs for each Pipeline Stage
    - Create a new tab called All to show all Customers
    - Add counters to each tab to show how many Customers are in each group
8. **[SoftDeletes: Archive and Restore Customers](https://github.com/tqt97/laravel-crm-filament/commit/92c4523cd3bc408a8698bced145928043c4e98f8)**
    - Add the Archived tab to the Customers table
    - Add Delete button to the table
    - Add the Restore button to the Archived tab
    - Disable row click on the Archived tab
9. **[Customer View Page with Infolist](https://github.com/tqt97/laravel-crm-filament/commit/59beb89b72011939b720d8a7ef33d3f7c99a717d)**
10. **[Customer Documents: Upload/Download](https://github.com/tqt97/laravel-crm-filament/commit/c18cef80ab740af812dd5cca90f25ae761db6e72)**
11. **[Custom Fields for Customers](https://github.com/tqt97/laravel-crm-filament/commit/db6e66bf036002f98a166fbfabd046bf48262879)**
12. **[Customers in a Draggable Kanban Board](https://github.com/tqt97/laravel-crm-filament)**
    - Creating Custom Page - Our Customer Board ```php artisan make:filament-page ManageCustomerStages```
13. **[Roles/Permissions: Manage Employees](https://github.com/tqt97/laravel-crm-filament)**
    - Creating Roles Model and Database structure
    - Creating Users Resource
    - Adding Employees to Customers
    - Adding Employee Changes to Customer History
    - Limiting Employee Access
14. **[Employee User Invitations Process](https://github.com/tqt97/laravel-crm-filament)**
    - Create Invitation Model and Database tables
    - Modify UserResource Create Button Action - to Invite the Employee
    - Creating Custom Registration Page
    - Creating and Sending the Email
15. **[Customer Tasks and Calendar View](https://github.com/tqt97/laravel-crm-filament)**
    - Create Task Model and Database
    - Add Create Task button to the Customer list
    - Add Task List to the Customer Page
    - Add Task Resource with Tabs
    - Adding Tabs to the Task Resource
    - Add a Calendar Page for Tasks
16. **[Create Customer Quotes with Products](https://github.com/tqt97/laravel-crm-filament)**
    - Creating the Product Model
    - Creating Product Resource
    - Creating the Quote Model
    - Creating Quote Resource
    - Create Quotes From Customer Table
17. **[Generate Quote PDF](https://github.com/tqt97/laravel-crm-filament)**
    - Creating a Simple View Page for Quote
    - Installing PDF Package
    - Generating PDF
    - Displaying PDF in View Page

