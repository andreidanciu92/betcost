<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Bet;
use Illuminate\Support\Facades\Auth;

class BetsController extends Controller
{
    public function store(Request $request)
    {

        $user = Auth::id();
        $bets = $request->input('bets');

        // PLACE BET ACCORDING TO WHAT THE USER HAS CHOSEN
        if(!empty($bets)) {

            foreach ($bets AS $id_match => $chosen_team) {
                Bet::create([
                    'match_id' => $id_match,
                    'user_id' => $user,
                    'team_user_bet_on' => $chosen_team
                ]);
            }
        }

        return redirect(route('home'));
    }
}
