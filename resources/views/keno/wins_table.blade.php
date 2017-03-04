
<div class="wins_table">
    <h3>Wins Table</h3>
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
                    @if (array_key_exists($i, $wins))
                        <td>x{{ $wins[$i] }}</td>
                    @else
                        <td></td>
                    @endif
                @endfor
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
