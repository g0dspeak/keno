
<div class="wins_table">
    <h3>Wins Results</h3>
    <table class="table table-responsive table-bordered">
        <thead>
        <tr>
            <th colspan="2" rowspan="2"></th>
            <th class="amount_table_header" colspan="11">Amount of intersected numbers</th>
        </tr>
        <tr>
            @for ($i = \App\Keno\WinsTable::MAX_NUMBERS_AMOUNT; $i > -1; $i--)
                <th>{{ $i }}</th>
            @endfor
        </tr>
        </thead>
        <tbody>
        <tr>
            <th rowspan="11" class="amount_intersected_header"><span>Amount of chosen numbers</span></th>
        </tr>
        @foreach ($winsTable as $number => $wins)
            <tr>
                <th>{{ $number }}</th>
                @for ($i = \App\Keno\WinsTable::MAX_NUMBERS_AMOUNT; $i > -1; $i--)
                    <td class="win_cell" id="win_cell_{{ $number }}_{{ $i }}"></td>
                @endfor
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="wins_result">
    <h3>You total spent: <span class="total_spent"></span></h3>
    <h3>You total won: <span class="total_win"></span></h3>
    <h3>Your profit is: <span class="total_profit"></span></h3>
    <h4 class="error-info-helper-block">Game was interrupted due to timeout error</h4>
</div>
