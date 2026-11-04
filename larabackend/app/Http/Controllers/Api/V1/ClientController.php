<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Exception;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClientPortalInviteMail;

class ClientController extends Controller {
    
    protected ClientService $clientService;

    public function __construct(ClientService $clientService) {
        $this->clientService = $clientService;
    }

    public function index(Request $request) {
        $filters = $request->only('search', 'type', 'status');
        $clients = $this->clientService->getClients($filters);
        return response()->json(['success' => true, 'data' => $clients]);
    }

    public function store(StoreClientRequest $request) {
        $client = $this->clientService->createClient($request->validated());
        return response()->json(['success' => true, 'message' => 'Client created successfully', 'client' => $client], 201);
    }

    public function update(UpdateClientRequest $request, Client $client) {
        $updatedClient = $this->clientService->updateClient($client, $request->validated());
        return response()->json(['success' => true, 'message' => 'Client updated successfully', 'client' => $updatedClient]);
    }

    public function destroy(Client $client) {
        $this->clientService->deleteClient($client);
        return response()->json(['success' => true, 'message' => 'Client deleted successfully']);
    }


     public function clientsStatus(Request $request, Client $client){
        try {
            $request->validate(['status' => 'required|in:0,1']);
            $client->update(['status' => $request->status]);
            $message = $request->status == 1 ? 'Client activated successfully' : 'Client deactivated successfully';
            return response()->json([
                'success' => true,
                'message' => $message,
                'client' => $client->only(['id', 'name', 'email', 'status'])
            ], 200);
        } catch (\Exception $e) { 
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }


    public function sendPortalInvite(Client $client) {
        try {
            $tempPassword = Str::random(8);
            $client->update([
                'password' => Hash::make($tempPassword),
                'status' => 1
            ]);
            Mail::to($client->email)->send(new ClientPortalInviteMail(
                $client->name,
                $client->email,
                $tempPassword
            ));
            return response()->json([
                'success' => true,
                'message' => 'Portal invite sent successfully to ' . $client->email
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send invite: ' . $e->getMessage()
            ], 500);
        }
    }

    


}