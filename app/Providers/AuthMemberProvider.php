<?php
namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;

class AuthMemberProvider extends EloquentUserProvider
{

    /**
     * Retrieve a user by their unique identifier.
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
       
        return $this->createModel()->newQuery()
//            ->leftJoin('companies', 'comp_members.company_id', '=', 'companies.id')
            ->join('companies', 'comp_members.company_id', '=', 'companies.id')
            ->whereNull('companies.deleted_at')
            ->find($identifier, ['comp_members.*','companies.id as companies_id']);
    }
}