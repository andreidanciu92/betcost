<?php

namespace App\Http\Controllers;

use App\Bet;
use App\User;
use Illuminate\Support\Facades\DB;

use App\Match;
use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TeamsController;
use function GuzzleHttp\Promise\all;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $matches_to_be_bet_on = array();

        $user = Auth::user();

        if($user) {
            if ($user->is_admin) {

                // GET ACTIVE TEAMS -> TEAMS THAT ARE STILL IN THE TOURNAMENT
                $teams = Team::where('is_active', 1)
                    ->where('is_idle', 0)
                    ->orderBy('name')
                    ->get();

                // GET LIST OF CONCLUDED MATCHES WITHOUT WINNER (start_date +2h )
                $concluded_matches =
                    Match::where('is_over', 0)
                        ->select('team1.name AS team_a_name',
                            'team2.name AS team_b_name',
                            'matches.*')
                        ->join('teams AS team1', 'team1.id', '=', 'matches.team_a')
                        ->join('teams AS team2', 'team2.id', '=', 'matches.team_b')
                        ->where('end_date', '<', Carbon::now())
                        ->get();

                return view('home_bo', ['teams' => $teams, 'concluded_matches' => $concluded_matches]);

            } else {

                // GET LIST OF MATCHES THAT CAN BE BET ON ( LIMIT IS 30 MIN BEFORE start_date )
                $matches_to_be_bet_on =
                    Match::where('is_over', 0)
                        ->select(
                            'team1.name AS team_a_name',
                            'team2.name AS team_b_name',
                            'team1.flag AS team_a_flag',
                            'team2.flag AS team_b_flag',
                            'matches.*')
                        ->join('teams AS team1', 'team1.id', '=', 'matches.team_a')
                        ->join('teams AS team2', 'team2.id', '=', 'matches.team_b')
                        ->where('start_date', '>', 'NOW() - INTERVAL 30 MINUTE')
                        ->get();

                // MATCHES THAT THE USER HAS ALREADY BET ON
                $matches_user_has_bet_already = Bet::where('user_id', $user->id)->get();

                if($matches_to_be_bet_on) {
                    // RIMUOVI DALL'ARRAY TUTTI I MATCH SUI QUALI L'UTENTE HA GIA' SCOMMESSO
                    foreach ($matches_user_has_bet_already as $match_already_bet) {
                        foreach ($matches_to_be_bet_on as $k => $match_to_be_bet) {
                            if ($match_to_be_bet->id == $match_already_bet->match_id) {
                                unset($matches_to_be_bet_on[$k]);
                            }
                        }
                    }
                }

                // GET ALL BETS FROM ALL USERS
                $all_bets =
                    Bet::select(
                        'bets.id AS id_bet',
                        'bets.user_won',
                        'bets.user_id',
                        'bets.created_at AS data_scommessa',
                        'team1.name AS team_a_name',
                        'team2.name AS team_b_name',
                        'users.name AS user_name',
                        'team_user_bet_on.name AS team_user_bet_on_name',
                        'matches.is_over')
                        ->join('users', 'bets.user_id', '=', 'users.id')
                        ->join('matches', 'bets.match_id', '=', 'matches.id')
                        ->join('teams AS team1', 'team1.id', '=', 'matches.team_a')
                        ->join('teams AS team2', 'team2.id', '=', 'matches.team_b')
                        ->join('teams AS team_user_bet_on', 'team_user_bet_on.id', '=', 'bets.team_user_bet_on')
                        ->orderBy('bets.created_at', 'desc')
                        //->where('start_date', '<', 'NOW() - INTERVAL 30 MINUTE') @TODO finish this
                        ->get();

                // GET ALL USERS AND CALCULATE RANKING
                $ranking = array();

                $users = User::all();
                foreach ($users AS $user) {
                    $ranking[$user->id] = 0;
                }

                foreach ($all_bets AS $bet) {
                    if($bet->user_won) {
                        $ranking[$bet->user_id] += 1;
                    }
                }

                arsort($ranking);

                return view('home',
                    [
                        'matches_to_be_bet_on' => $matches_to_be_bet_on,
                        'all_bets' => $all_bets,
                        'ranking' => $ranking
                    ]);
            }
        } else {
            return view('/');
        }
    }

}
