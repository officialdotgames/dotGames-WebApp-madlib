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
            'party' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
        ]);

        $party = Party::where('party_code', $request->input('party'))->first();
        if(is_null($party)){
            return redirect()->back()->withErrors('Error: Invalid room code.');
        }

        $player = Player::create([
          'name' => $request->input('nickname')
        ]);
        $party->players()->attach($player);

        return redirect('/')->with('success', 'Welcome '.$request->input('nickname').'!');
    }
}
