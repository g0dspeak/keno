
<form>
    <div id="keno_form" class="form-container" data-form-container>
        <div class="row">
            <div class="form-title">
                <h1>Play Keno</h1>
            </div>
        </div>
        <div class="input-container">

            <h4><b>Combination:</b></h4>

            <div class="combination"></div>

            <div class="row">
                <br/>
                <div class="combination-keyboard" type="keyboard">
                    @for ($i = 1; $i < 71; $i++)
                        <input class="combination_numbers" name="combination[]" id="combination_{{ $i }}" type="checkbox" value="{{ $i }}" />
                        <label for="combination_{{ $i }}"></label>

                        <style>
                            #combination_{{ $i }}+label:after {
                                content: '{{ $i }}';
                            }
                        </style>
                    @endfor
                </div>
            </div> <br/>


            <h4><b>Money rate:</b></h4>
            <div class="row money_rate">
                <span class="req-input" >
                    <span class="input-status" data-toggle="tooltip" data-placement="top" title="Input Your Money rate"> </span>
                    <input name="money_rate" type="number" data-min-length="1" placeholder="Your money rate" value="1" min="1" max="999999">
                </span>
            </div> <br/>


            <h4><b>Play until:</b></h4>

            <br/>
            <div class="play_until">
                <input type="radio" id="games_amount" name="play_until" value="{{ \App\Keno\Game::GAMES_AMOUNT }}" checked />
                <label for="games_amount">Amount of games</label>

                <br/><input type="radio" id="max_win" name="play_until" value="{{ \App\Keno\Game::MAX_WIN }}"/>
                <label for="max_win">Max win</label>

                <div class="row games_amount">
                    <span class="req-input" >
                        <span class="input-status" data-toggle="tooltip" data-placement="top" title="Input Amount of games"> </span>
                        <input name="games_amount" type="number" data-min-length="1" placeholder="Amount of games" value="1" min="1" max="999999">
                    </span>
                </div>

                <div class="row max_win">
                    <span class="req-input" >
                        <span class="input-status" data-toggle="tooltip" data-placement="top" title="Choose max win"> </span>
                        <select id="max_win_options" name="max_win[]" type="select">
                            {!! $promptOption !!}
                        </select>
                    </span>
                </div>
            </div>

            <div class="row submit-row">
                <div class="col-md-5"><button type="button" class="btn btn-block reset-form">Reset</button></div>
                <div class="col-md-2"></div>
                <div class="col-md-5"><button type="submit" class="btn btn-block submit-form">Play</button></div>
            </div>
        </div>
    </div>
</form>

<script>
    var WinsTable = JSON.parse('<?php echo json_encode($winsTable)?>');
    var promptOption = '{!! $promptOption !!}';
    var _token = '{{ csrf_token() }}';
</script>
