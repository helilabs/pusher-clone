<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

Class PushRequest extends Model{

    public $dates = [
        'sent_at'
    ];

    public $fillable = [
        'channel', 'payload', 'sent_at'
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /*----------------------------------------------------
    * methods
    --------------------------------------------------- */
    /**
     * Push Request has been sent to connected clients
     *
     * @return void
     */
    public function sent()
    {
        $this->sent_at = Carbon::now()->toDateTimeString();
        $this->save();
    }

    /**
     * Send Push Request to ZeroMQ
     *
     * @return void
     */
    public function queue($app_id)
    {
        app('zmq')->send(json_encode([
            'channel' => $this->channel,
            'app_id' => $app_id,
            'payload' => $this->payload
        ]));
    }

    /*----------------------------------------------------
    * Attributes
    --------------------------------------------------- */
    /**
     * create is_sent attributes which clarifies if request has been sent to connected clients or not
     *
     * @return boolean
     */
    public function getIsSentAttribute()
    {
        if ($this->sent_at) {
            return true;
        }

        return false;
    }

    public function getDecodedPayloadAttribute(){
        return json_decode($this->payload);
    }
}