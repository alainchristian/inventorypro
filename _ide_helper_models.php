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
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog byAction($action)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog byTable($tableName)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog byUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog recent($days = 7)
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $location_id
 * @property string $product_id
 * @property string $description
 * @property string $status
 * @property int $reported_by
 * @property \Illuminate\Support\Carbon $reported_at
 * @property int|null $resolved_by
 * @property \Illuminate\Support\Carbon|null $resolved_at
 * @property bool $replacement_given
 * @property int $damaged_units
 * @property int $box_opened
 * @property string|null $resolution_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Location $location
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $reporter
 * @property-read \App\Models\User|null $resolver
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint resolved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint supplierNotified()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereBoxOpened($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereDamagedUnits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereReplacementGiven($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereReportedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereReportedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereResolutionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereResolvedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereResolvedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereUpdatedAt($value)
 */
	class Complaint extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $location_id
 * @property string $product_id
 * @property int $boxes
 * @property int $loose_units
 * @property int $damaged_units
 * @property \Illuminate\Support\Carbon $last_updated
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_low_stock
 * @property-read mixed $stock_value
 * @property-read mixed $total_units
 * @property-read \App\Models\Location $location
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereBoxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereDamagedUnits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereLastUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereLooseUnits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereUpdatedAt($value)
 */
	class Inventory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $manager_name
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Complaint> $complaints
 * @property-read int|null $complaints_count
 * @property-read mixed $is_shop
 * @property-read mixed $is_warehouse
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory> $inventory
 * @property-read int|null $inventory_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sale> $sales
 * @property-read int|null $sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transfer> $transfersFrom
 * @property-read int|null $transfers_from_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transfer> $transfersTo
 * @property-read int|null $transfers_to_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location shops()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location warehouses()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereManagerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location withoutTrashed()
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property int $units_per_box
 * @property numeric|null $purchase_price
 * @property numeric $selling_price
 * @property int $min_stock_level
 * @property string|null $supplier_name
 * @property string|null $supplier_phone
 * @property string|null $barcode
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Complaint> $complaints
 * @property-read int|null $complaints_count
 * @property-read mixed $profit_margin
 * @property-read mixed $profit_per_box
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventory> $inventory
 * @property-read int|null $inventory_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sale> $sales
 * @property-read int|null $sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transfer> $transfers
 * @property-read int|null $transfers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product lowStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereMinStockLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSupplierName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSupplierPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnitsPerBox($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withoutTrashed()
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $location_id
 * @property string $product_id
 * @property int $quantity
 * @property numeric $unit_price
 * @property numeric $total_amount
 * @property int $sold_by
 * @property \Illuminate\Support\Carbon $sold_at
 * @property string|null $customer_name
 * @property string|null $customer_phone
 * @property string $receipt_number
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $formatted_receipt_number
 * @property-read \App\Models\Location $location
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $seller
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale byLocation($locationId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale byProduct($productId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale dateRange($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale thisMonth()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale thisWeek()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCustomerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereReceiptNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereSoldAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereSoldBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUpdatedAt($value)
 */
	class Sale extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $from_location_id
 * @property int $to_location_id
 * @property string $product_id
 * @property int $quantity
 * @property string $status
 * @property int|null $requested_by
 * @property \Illuminate\Support\Carbon $requested_at
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property int|null $received_by
 * @property \Illuminate\Support\Carbon|null $received_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\Location $fromLocation
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User|null $receiver
 * @property-read \App\Models\User|null $requester
 * @property-read \App\Models\Location $toLocation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer inTransit()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereFromLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereReceivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereReceivedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereRequestedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereRequestedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereToLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereUpdatedAt($value)
 */
	class Transfer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property int|null $location_id
 * @property bool $is_active
 * @property string|null $phone
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ActivityLog> $activityLogs
 * @property-read int|null $activity_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Complaint> $complaintsReported
 * @property-read int|null $complaints_reported_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Complaint> $complaintsResolved
 * @property-read int|null $complaints_resolved_count
 * @property-read \App\Models\Location|null $location
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sale> $sales
 * @property-read int|null $sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transfer> $transfersApproved
 * @property-read int|null $transfers_approved_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transfer> $transfersReceived
 * @property-read int|null $transfers_received_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transfer> $transfersRequested
 * @property-read int|null $transfers_requested_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User atLocation($locationId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byRole($role)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

