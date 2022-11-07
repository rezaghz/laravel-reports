# Laravel Reports

Laravel reports package for implementing reports (eg: Spam,Violence,Child Abuse,illegal Drugs etc etc) on Eloquent
models.

## Installation

Download package into the project using Composer.

```bash
$ composer require rezaghz/laravel-reports
```

### Registering package

> Laravel 5.5 (or higher) uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

For Laravel 5.4 or earlier releases version include the service provider within `app/config/app.php`:

```php
'providers' => [
    Rezaghz\Laravel\Reports\ReportsServiceProvider::class,
],
```

### Database Migration

If you want to make changes in migrations, publish them to your application first.

```bash
$ php artisan vendor:publish --provider="Rezaghz\Laravel\Reports\ReportsServiceProvider" --tag=migrations
```

Run database migrations.

```bash
$ php artisan migrate
```

## Usage

### Prepare Reports (User) Model

Use `Rezaghz\Laravel\Reports\Contracts\ReportsInterface` contract in model which will perform report behavior on
reportable model and implement it and use `Rezaghz\Laravel\Reports\Traits\Reports` trait.

```php
use Rezaghz\Laravel\Reports\Traits\Reports;
use Rezaghz\Laravel\Reports\Contracts\ReportsInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements ReportsInterface
{
    use Reports;
}
```

### Prepare Reportable Model

Use `Rezaghz\Laravel\Reports\Contracts\ReportableInterface` contract in model which will get report behavior and
implement it and use `Rezaghz\Laravel\Reports\Traits\Reportable` trait.

```php
use Illuminate\Database\Eloquent\Model;
use Rezaghz\Laravel\Reports\Traits\Reportable;
use Rezaghz\Laravel\Reports\Contracts\ReportableInterface;

class Article extends Model implements ReportableInterface
{
    use Reportable;
}
```

## Available Methods

### Report

```php
$user->reportTo($article, 'spam');

$article->report('spam'); // current login user
$article->report('spam', $user);
```

### Remove Report

Removing report of user from reportable model.

```php
$user->removeReportFrom($article);

$article->removeReport(); // current login user
$article->removeReport($user);
```

### Toggle Report

The toggle report method will add a report to the model if the user has not reported. If a user has already reported,
then it will replace the previous report with a new report. For example, if the user has reported 'spam' on the model.
Now on toggles report to 'violence' then it will remove the 'spam' and stores the 'violence' report.

If a user has reported `spam` then on toggle report with `spam`. It will remove the report.

```php
$user->toggleReportOn($article, 'spam');

$article->toggleReport('spam'); // current login user
$article->toggleReport('spam', $user);
```

### Boolean check if user reported on model

```php
$user->isReportedOn($article));

$article->is_reported; // current login user
$article->isReportBy(); // current login user
$article->isReportBy($user);
```

### Report summary on model

```php
$article->reportSummary();
$article->report_summary;

// example
$article->report_summary->toArray();
// output
/*
[
    "spam" => 5,
    "violence" => 2,
    "illegal_drugs" => 4,
    "child_abuse" => 1
]
*/
```

### Get collection of users who reported on model

```php
$article->reportsBy();
```

### Scopes

Find all articles reported by user.

```php
Article::whereReportedBy()->get(); // current login user

Article::whereReportedBy($user)->get();
Article::whereReportedBy($user->id)->get();
```

### Report on Model

```php
// It will return the Report object that is reported by given user.
$article->reported($user);
$article->reported(); // current login user
$article->reported; // current login user

$user->reportedOn($article);
```

### Events

On each report added `\Rezaghz\Laravel\Reports\Events\OnReport` event is fired.

On each report removed `\Rezaghz\Laravel\Reports\Events\OnDeleteReport` event is fired.

### Testing

Run the tests with:

```bash
$ vendor/bin/phpunit
```
