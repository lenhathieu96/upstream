<!-- resources/views/exports/farmers_transactions.blade.php -->

<table>
    <thead>
    <tr>
        <th>Farmer Name</th>
        <th>Farmer ID</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($farmers as $farmer)
        <tr>
            <td>{{ $farmer->name }}</td>
            <td>{{ $farmer->id }}</td>
            <td>{{ $farmer->balance }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach ($farmer->transactions as $transaction)
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $transaction->transaction_date }}</td>
                <td>{{ $transaction->receipt_number }}</td>
                <td>{{ $transaction->initial_balance }}</td>
                <td>{{ $transaction->transaction_amount }}</td>
                <td>{{ $transaction->balance_amount }}</td>
                <td>{{ $transaction->transaction_type }}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
