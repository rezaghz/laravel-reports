<?php

namespace Rezaghz\Tests\Laravel\Reports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Rezaghz\Laravel\Reports\Traits\Reportable;
use Rezaghz\Laravel\Reports\Traits\Reports;
use Rezaghz\Laravel\Reports\Contracts\ReportsInterface;
use Rezaghz\Laravel\Reports\Contracts\ReportableInterface;

class ReportableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test models
        $this->createUsersTable();
        $this->createPostsTable();
    }

    #[Test]
    public function it_can_report_on_a_model()
    {
        $user = TestUser::create(['name' => 'John Doe']);
        $post = TestPost::create(['title' => 'Test Post']);

        $report = $post->report('spam', $user);

        $this->assertNotNull($report);
        $this->assertEquals('spam', $report->type);
        $this->assertEquals($user->id, $report->user_id);
        $this->assertEquals($post->id, $report->reportable_id);
        $this->assertEquals(TestPost::class, $report->reportable_type);
    }

    #[Test]
    public function it_can_check_if_model_is_reported_by_user()
    {
        $user = TestUser::create(['name' => 'John Doe']);
        $post = TestPost::create(['title' => 'Test Post']);

        $this->assertFalse($post->isReportBy($user));

        $post->report('spam', $user);

        $this->assertTrue($post->isReportBy($user));
        $this->assertTrue($post->isReportBy($user, 'spam'));
        $this->assertFalse($post->isReportBy($user, 'violence'));
    }

    #[Test]
    public function it_can_remove_report()
    {
        $user = TestUser::create(['name' => 'John Doe']);
        $post = TestPost::create(['title' => 'Test Post']);

        $post->report('spam', $user);
        $this->assertTrue($post->isReportBy($user));

        $post->removeReport($user);
        $this->assertFalse($post->isReportBy($user));
    }

    #[Test]
    public function it_can_toggle_report()
    {
        $user = TestUser::create(['name' => 'John Doe']);
        $post = TestPost::create(['title' => 'Test Post']);

        // First report
        $report = $post->toggleReport('spam', $user);
        $this->assertNotNull($report);
        $this->assertTrue($post->isReportBy($user, 'spam'));

        // Toggle same type - should remove
        $post->toggleReport('spam', $user);
        $this->assertFalse($post->isReportBy($user, 'spam'));

        // Toggle different type - should add new report
        $report = $post->toggleReport('violence', $user);
        $this->assertNotNull($report);
        $this->assertTrue($post->isReportBy($user, 'violence'));
    }

    #[Test]
    public function it_can_get_report_summary()
    {
        $user1 = TestUser::create(['name' => 'John Doe']);
        $user2 = TestUser::create(['name' => 'Jane Doe']);
        $user3 = TestUser::create(['name' => 'Bob Smith']);
        $user4 = TestUser::create(['name' => 'Alice Johnson']);
        $post = TestPost::create(['title' => 'Test Post']);

        $post->report('spam', $user1);
        $post->report('spam', $user2);
        $post->report('violence', $user3);
        $post->report('violence', $user4);

        $summary = $post->reportSummary;

        $this->assertEquals(2, $summary['spam']);
        $this->assertEquals(2, $summary['violence']);
    }

    private function createUsersTable()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    private function createPostsTable()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('posts', function ($table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });
    }
}

class TestUser extends Model implements ReportsInterface
{
    use Reports;

    protected $table = 'users';
    protected $fillable = ['name'];
}

class TestPost extends Model implements ReportableInterface
{
    use Reportable;

    protected $table = 'posts';
    protected $fillable = ['title'];
} 