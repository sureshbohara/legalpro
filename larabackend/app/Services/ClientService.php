<?php
namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;

class ClientService {

    
    public function getClients(array $filters = []) {
        return Client::query()
            ->when(!empty($filters['search']), function (Builder $q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            })
            ->when(!empty($filters['type']), function (Builder $q) use ($filters) {
                $q->where('type', $filters['type']);
            })
            ->when(isset($filters['status']), function (Builder $q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function createClient(array $data): Client {
        return Client::create($data);
    }

    public function updateClient(Client $client, array $data): Client {
        $client->update($data);
        return $client->fresh();
    }

    public function deleteClient(Client $client): bool {
        return $client->delete();
    }
}