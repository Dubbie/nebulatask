<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\BoardSection
 *
 * @property int $id
 * @property string $project_id
 * @property string $name
 * @property string $type
 * @property int $sequence
 * @property int $is_collapsed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Issue> $issues
 * @property-read int|null $issues_count
 * @property-read \App\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection query()
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection type(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection whereIsCollapsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BoardSection whereUpdatedAt($value)
 */
	class BoardSection extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Issue
 *
 * @property string $id
 * @property int $board_section_id
 * @property int|null $user_id
 * @property string $title
 * @property string|null $description
 * @property string|null $due_date
 * @property int $sequence
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $parent_issue_id
 * @property-read \App\Models\User|null $assignee
 * @property-read \App\Models\BoardSection $boardSection
 * @property-read mixed $is_complete
 * @property-read Issue|null $parent
 * @property-read \App\Models\Project|null $project
 * @property-read mixed $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Issue> $subIssues
 * @property-read int|null $sub_issues_count
 * @property-read mixed $type
 * @method static \Illuminate\Database\Eloquent\Builder|Issue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Issue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Issue query()
 * @method static \Illuminate\Database\Eloquent\Builder|Issue rootIssues()
 * @method static \Illuminate\Database\Eloquent\Builder|Issue whereBoardSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Issue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Issue whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Issue whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Issue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Issue whereParentIssueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Issue whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Issue whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Issue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Issue whereUserId($value)
 */
	class Issue extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\IssueStatus
 *
 * @property-read mixed $formatted_name
 * @method static \Illuminate\Database\Eloquent\Builder|IssueStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IssueStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IssueStatus query()
 */
	class IssueStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property int $user_id
 * @property int $issue_counter
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BoardSection> $boardSections
 * @property-read int|null $board_sections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Issue> $issues
 * @property-read int|null $issues_count
 * @property-read \App\Models\User $lead
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIssueCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUserId($value)
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

