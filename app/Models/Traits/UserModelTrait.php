<?php


namespace App\Models\Traits;

use App\Models\PaymentHistory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission;

trait UserModelTrait
{

    use LogsActivity;
    //region Logs Activity Properties
    public static $logMessage = null;
    protected static $logName = 'user';
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        if(static::$logMessage != null){
            return static::$logMessage;
        }

        if($eventName == 'created'){
            static::$logName= 'user.create';
            return "Yeni kullanıcı oluşturuldu.";
        }elseif($eventName == 'updated'){
            static::$logName= 'user.update';
            return "Kullanıcı güncellendi.";
        }elseif($eventName == 'deleted'){
            static::$logName= 'user.delete';
            return "Kullanıcı silindi.";
        }else{
            return "{$eventName}";
        }
    }
    //endregion

    /**
     *
     * @return bool
     */
    public function hasVerifiedTC()
    {
        return ! is_null($this->tc_verified_at);
    }

    //RELATIONSHIPS

    // GETTERS
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
    public function getNotificationsAttribute(){
        return $this->notifications()->get();
    }
    public function getUnreadNotificationsAttribute(){
        return $this->unreadNotifications()->get();
    }
    public function getIsOnlineAttribute()
    {
        return $this->isOnline();
    }
    public function getIsAdminAttribute()
    {
        return $this->hasRole(['admin','super-admin']);
    }
    public function getPermissionsAllAttribute()
    {
        return $this->getAllPermissions()->transform(function(Permission $item){
            return $item->only('name','description');
        });
    }
    public function getRoleTextAttribute()
    {
        return $this->roles->pluck('description')->implode(',');
    }

    // SETTERS
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = \Str::ucfirst($value);
    }
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = \Str::upper($value);
    }
}
