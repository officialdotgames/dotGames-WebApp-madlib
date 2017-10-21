<?php

namespace App\Http\Controllers;

use App\Party;
use App\Player;
use Illuminate\Http\Request;
use Validator;

class MainController extends Controller
{
    public function Index() {
        return view('index');
    }

    public function JoinParty(Request $request) {
        $validator = Validator::make($request->all(), [
            'party_code' => 'required|string|max:6',
            'nickname' => 'required|string|max:255',
        ]);

        $party = Party::where('party_code', $request->input('party_code'))->first();
        if(is_null($party)){
            return redirect()->back()->withErrors('Error: Invalid room code.');
        }

        $player = Player::create([
          'name' => $request->input('nickname')
        ]);
        $party->players()->attach($player);


        session( [ 'party_id' => $party->id ] );
        return redirect('game')
                    ->with('success', 'Welcome '.$request->input('nickname').'!');
    }

    public function ShowGame() {
        $party_id = session( 'party_id' );
        
        return view('game', compact($party_id));
    }

    public function SubmitMadLib(Request $request) {

        //todo handle prompt index
        $validator = Validator::make($request->all(), [
            'party_id' => 'required|integer|max:12',
            'madlib' => 'required|string|max:255',
            'prompt_index' => 'required|integer|max:12'
        ]);

        $party = Party::where('party_id', $request->input('party_id'))->first();
        if(is_null($party)){
            return redirect()->back()->withErrors('Error: Party does not exist.');
        }


    }
}
