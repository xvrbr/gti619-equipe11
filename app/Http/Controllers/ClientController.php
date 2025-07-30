<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use illuminate\Support\Facades\Crypt;

class ClientController extends Controller
{
    /**
     * Display a listing of residential clients.
     *
     * @return \Illuminate\Http\Response
     */
    public function residential()
    {
        $clients = Client::where('type', 'residentiel')->get();
        return view('clients.residential', compact('clients'));
    }

    /**
     * Display a listing of business clients.
     *
     * @return \Illuminate\Http\Response
     */
    public function business()
    {
        $clients = Client::where('type', 'affaire')->get();
        return view('clients.business', compact('clients'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all()->toArray();
        return view('client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { $this->validate($request, [
        'first_name'    =>  'required',
        'last_name'     =>  'required'
    ]);
    $client = new client([
        'first_name'    =>  $request->get('first_name'),
        'last_name'     =>  $request->get('last_name')
    ]);
    $client->save();
    
    // Chiffrer les informations sensibles avant de les stocker
    session(['secret_info' => Crypt::encryptString($client->first_name . ' ' . $client->last_name)]);

    return redirect()->route('client.index')->with('success', 'Client ajouté');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Récupération et déchiffrement
        $info = null;
        if (session()->has('secret_info')) {
        $info = Crypt::decryptString(session('secret_info'));
        }
    
        return view('client.show', compact('info'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::find($id);
        return view('client.edit', compact('client', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name'    =>  'required',
            'last_name'     =>  'required'
        ]);
        $client = client::find($id);
        $client->first_name = $request->get('first_name');
        $client->last_name = $request->get('last_name');
        $client->save();
        return redirect()->route('client.index')->with('success', 'Client modifié');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = client::find($id);
        $client->delete();
        return redirect()->route('client.index')->with('success', 'Client supprimé');
    }
}
