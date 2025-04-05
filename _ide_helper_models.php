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
 * 
 *
 * @property-read \App\Models\Assembly|null $assembly
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Baptism> $baptisms
 * @property-read int|null $baptisms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medias
 * @property-read int|null $medias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messagesReceived
 * @property-read int|null $messages_received_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messagesSent
 * @property-read int|null $messages_sent_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resource> $resources
 * @property-read int|null $resources_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Roles> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static Builder|User filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static Builder|Admin applyFilters(array $filters)
 * @mixin \Eloquent
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $sector_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medias
 * @property-read int|null $medias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\Sector $sector
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static Builder|Assembly applyFilters(array $filters)
 * @method static \Database\Factories\AssemblyFactory factory($count = null, $state = [])
 * @method static Builder|Assembly filter(array $filters)
 * @method static Builder|Assembly newModelQuery()
 * @method static Builder|Assembly newQuery()
 * @method static Builder|Assembly query()
 * @method static Builder|Assembly whereCreatedAt($value)
 * @method static Builder|Assembly whereDescription($value)
 * @method static Builder|Assembly whereId($value)
 * @method static Builder|Assembly whereName($value)
 * @method static Builder|Assembly whereSectorId($value)
 * @method static Builder|Assembly whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Assembly extends \Eloquent implements \App\Interfaces\ReportableModel, \App\Interfaces\FilterableModel {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $assembly_id
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Assembly $assembly
 * @property-read \App\Models\Event $event
 * @method static \Database\Factories\AssemblyEventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent whereAssemblyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class AssemblyEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $assembly_id
 * @property int $media_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\AssemblyMediaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia whereAssemblyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class AssemblyMedia extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $assembly_id
 * @property int $message_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\AssemblyMessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage whereAssemblyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class AssemblyMessage extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $type Values: none, water, holy-spirit, both-water-and-holy-spirit
 * @property string|null $nominalMaker
 * @property int $hasHolySpirit
 * @property string|null $ministerialLevel
 * @property int $spiritualLevel
 * @property string|null $on
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\BaptismFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism query()
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereHasHolySpirit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereMinisterialLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereNominalMaker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereSpiritualLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereUserId($value)
 * @property \Illuminate\Support\Carbon|null $date_water
 * @property \Illuminate\Support\Carbon|null $date_holy_spirit
 * @property \Illuminate\Support\Carbon|null $date_latest
 * @method static Builder|Baptism whereDateHolySpirit($value)
 * @method static Builder|Baptism whereDateLatest($value)
 * @method static Builder|Baptism whereDateWater($value)
 * @mixin \Eloquent
 */
	class Baptism extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $resource_id
 * @property int $quantity
 * @property string|null $borrowed_at
 * @property string|null $returned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\BorrowedFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed query()
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereBorrowedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereReturnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereUserId($value)
 * @mixin \Eloquent
 */
	class Borrowed extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $reference
 * @property string $amount
 * @property string|null $donor_name
 * @property string|null $donor_email
 * @property string|null $donor_phone
 * @property \Illuminate\Support\Carbon $donation_date
 * @property string|null $message
 * @property bool $is_anonymous
 * @property int|null $user_id
 * @property int|null $payment_method_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PaymentMethod|null $paymentMethod
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Donation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Donation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Donation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereDonationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereDonorEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereDonorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereDonorPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereUserId($value)
 * @property int|null $transaction_id
 * @property string|null $payment_data
 * @property int $is_pending
 * @property int $is_completed
 * @property int $is_failed
 * @method static \Illuminate\Database\Eloquent\Builder|Donation applyFilters(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereIsCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereIsFailed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereIsPending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation wherePaymentData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereReference($value)
 * @mixin \Eloquent
 */
	class Donation extends \Eloquent implements \App\Interfaces\ReportableModel, \App\Interfaces\FilterableModel {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $status unavailable | ongoing | ended
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $from
 * @property \Illuminate\Support\Carbon|null $to
 * @property int|null $user_id Creator of the event
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assembly> $assemblies
 * @property-read int|null $assemblies_count
 * @property-read \App\Models\User|null $user
 * @method static Builder|Event applyFilters(array $filters)
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static Builder|Event filter(array $filters)
 * @method static Builder|Event newModelQuery()
 * @method static Builder|Event newQuery()
 * @method static Builder|Event query()
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereDescription($value)
 * @method static Builder|Event whereFrom($value)
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereStatus($value)
 * @method static Builder|Event whereTitle($value)
 * @method static Builder|Event whereTo($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @method static Builder|Event whereUserId($value)
 * @mixin \Eloquent
 */
	class Event extends \Eloquent implements \App\Interfaces\ReportableModel, \App\Interfaces\FilterableModel {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resource> $resources
 * @property-read int|null $resources_count
 * @method static \Database\Factories\GroupFactory factory($count = null, $state = [])
 * @method static Builder|Group filter(array $filters)
 * @method static Builder|Group newModelQuery()
 * @method static Builder|Group newQuery()
 * @method static Builder|Group query()
 * @method static Builder|Group whereCreatedAt($value)
 * @method static Builder|Group whereDescription($value)
 * @method static Builder|Group whereId($value)
 * @method static Builder|Group whereName($value)
 * @method static Builder|Group whereUpdatedAt($value)
 * @method static Builder|Group applyFilters(array $filters)
 * @mixin \Eloquent
 */
	class Group extends \Eloquent implements \App\Interfaces\ReportableModel, \App\Interfaces\FilterableModel {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $level
 * @property string $message
 * @property array|null $context
 * @property string $channel
 * @property int|null $user_id
 * @property string|null $ip_address
 * @property string|null $uri
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUserId($value)
 * @mixin \Eloquent
 */
	class Log extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $uri
 * @property string|null $comment
 * @property string $type The document type
 * @property int $user_id Owner/Sender of the document.
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\MediaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Media filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assembly> $assemblies
 * @property-read int|null $assemblies_count
 * @property int|null $sender_id
 * @property-read \App\Models\User|null $sender
 * @method static Builder|Media whereSenderId($value)
 * @mixin \Eloquent
 */
	class Media extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $subject
 * @property string|null $content
 * @property int|null $senderId
 * @property int|null $receiverId
 * @property int|null $assembly_id
 * @property string|null $category
 * @property string|null $picture_path
 * @property string|null $tags
 * @property int $received
 * @property int $seen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Assembly|null $assembly
 * @property-read \App\Models\User|null $receiver
 * @property-read \App\Models\User|null $sender
 * @method static \Database\Factories\MessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Message filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereAssemblyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message wherePicturePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @property int|null $message_id The id of the message we're replying to.
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assembly> $assemblies
 * @property-read int|null $assemblies_count
 * @property-read Message|null $message
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Message> $replies
 * @property-read int|null $replies_count
 * @method static Builder|Message whereMessageId($value)
 * @property int $deleted
 * @method static Builder|Message whereDeleted($value)
 * @mixin \Eloquent
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $description
 * @property string|null $logo_path
 * @property bool $is_active
 * @property string|null $phone_regex
 * @property string|null $phone_prefix
 * @property string|null $color_code
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereColorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod wherePhonePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod wherePhoneRegex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class PaymentMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $quantity
 * @property string|null $description
 * @property int $group_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Group $group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\ResourceFactory factory($count = null, $state = [])
 * @method static Builder|Resource filter(array $filters)
 * @method static Builder|Resource newModelQuery()
 * @method static Builder|Resource newQuery()
 * @method static Builder|Resource query()
 * @method static Builder|Resource whereCreatedAt($value)
 * @method static Builder|Resource whereDescription($value)
 * @method static Builder|Resource whereGroupId($value)
 * @method static Builder|Resource whereId($value)
 * @method static Builder|Resource whereName($value)
 * @method static Builder|Resource whereQuantity($value)
 * @method static Builder|Resource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Resource extends \Eloquent implements \App\Interfaces\ReportableModel {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $role_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Roles $role
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\RoleUserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereUserId($value)
 * @mixin \Eloquent
 */
	class RoleUser extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $displayName
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\RolesFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Roles filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Roles newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Roles query()
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Roles extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $master_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assembly> $assemblies
 * @property-read int|null $assemblies_count
 * @property-read Sector|null $master
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sector> $subsectors
 * @property-read int|null $subsectors_count
 * @method static Builder|Sector applyFilters(array $filters)
 * @method static \Database\Factories\SectorFactory factory($count = null, $state = [])
 * @method static Builder|Sector filter(array $filters)
 * @method static Builder|Sector newModelQuery()
 * @method static Builder|Sector newQuery()
 * @method static Builder|Sector query()
 * @method static Builder|Sector whereCreatedAt($value)
 * @method static Builder|Sector whereDescription($value)
 * @method static Builder|Sector whereId($value)
 * @method static Builder|Sector whereMasterId($value)
 * @method static Builder|Sector whereName($value)
 * @method static Builder|Sector whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Sector extends \Eloquent implements \App\Interfaces\ReportableModel, \App\Interfaces\FilterableModel {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\Sector|null $master
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sector> $subsectors
 * @property-read int|null $subsectors_count
 * @method static Builder|Subsector filter(array $filters)
 * @method static Builder|Subsector newModelQuery()
 * @method static Builder|Subsector newQuery()
 * @method static Builder|Subsector query()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assembly> $assemblies
 * @property-read int|null $assemblies_count
 * @method static Builder|Subsector applyFilters(array $filters)
 * @mixin \Eloquent
 */
	class Subsector extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property float $amount
 * @property string $currency
 * @property string|null $comment
 * @property int|null $user_id
 * @property string|null $donor_name
 * @property string|null $donor_email
 * @property string|null $donor_phone
 * @property int|null $payment_method_id
 * @property int $is_processed
 * @property string|null $transaction_reference
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Donation|null $donation
 * @property-read \App\Models\PaymentMethod|null $paymentMethod
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\TransactionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDonorEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDonorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDonorPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereIsProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTransactionReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUserId($value)
 * @mixin \Eloquent
 */
	class Transaction extends \Eloquent implements \App\Interfaces\ReportableModel {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $surname
 * @property string $gender
 * @property string $email
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $dateOfBirth
 * @property string|null $residence
 * @property string|null $phoneNumber
 * @property string|null $antecedent
 * @property string|null $profile_photo_path
 * @property int|null $current_team_id
 * @property int $isActive
 * @property \Illuminate\Support\Carbon|null $arrivalDate
 * @property string|null $maritalStatus
 * @property int $numberOfChildren
 * @property int $sterileWoman
 * @property string|null $seriousIllnesses
 * @property string|null $comment
 * @property int|null $assembly_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $remember_token
 * @property-read \App\Models\Assembly|null $assembly
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Baptism> $baptisms
 * @property-read int|null $baptisms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $documents
 * @property-read int|null $documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messagesReceived
 * @property-read int|null $messages_received_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messagesSent
 * @property-read int|null $messages_sent_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resource> $resources
 * @property-read int|null $resources_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Roles> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAntecedent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereArrivalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAssemblyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNumberOfChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereResidence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSeriousIllnesses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSterileWoman($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @property string|null $profession
 * @property int $isDisciplined
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medias
 * @property-read int|null $medias_count
 * @method static Builder|User whereIsDisciplined($value)
 * @method static Builder|User whereProfession($value)
 * @property string|null $profession_details
 * @method static Builder|User whereProfessionDetails($value)
 * @method static Builder|User applyFilters(array $filters)
 * @mixin \Eloquent
 */
	class User extends \Eloquent implements \App\Interfaces\ReportableModel, \App\Interfaces\FilterableModel {}
}

