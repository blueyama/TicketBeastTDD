<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    protected $guarded = [];
    protected $dates = ['date'];


    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * this is accessible in the blade view like so Concert::formatted_date
     * @return mixed
     */
    public function getFormattedDateAttribute()
    {
        return $this->date->format('F j, Y');
    }

    /**
     * this is accessible in the blade view like so Concert::formatted_start_time
     * @return mixed
     */
    public function getFormattedStartTimeAttribute()
    {
        return $this->date->format('g:ia');
    }

    /**
     * this is accessible in the blade view like so Concert::ticket_price_in_dollars
     * @return mixed
     */
    public function getTicketPriceInDollarsAttribute()
    {
        return number_format($this->ticket_price / 100, 2);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @param $email
     * @param $ticketQuantity
     * @return Model
     */
    public function orderTickets($email, $ticketQuantity)
    {
        $order = $this->orders()->create(['email' => $email]);

        foreach (range(1, $ticketQuantity) as $i) {
            $order->tickets()->create([]);
        }

        return $order;
    }
}
