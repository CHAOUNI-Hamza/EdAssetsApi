<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            'username' => $this->username,
            'nickname' => $this->nickname,
            'phone' => $this->phone,
            'role' => $this->roles,
            'cin' =>  $this->cin,
            'state' =>  $this->state,
        ];
    }
}
