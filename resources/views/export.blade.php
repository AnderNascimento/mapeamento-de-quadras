<div style="text-align:center">
    <div style="margin-bottom: 30px">
        <h1>PERÍMETRO DA QUADRA</h1>
    </div>

    <div>
        <img src="data:image/png;base64,{{ $image }}" alt="">
        <div style="margin-top: 60px">
            <table>
                <tbody>
                    <tr>
                        <td>Nome</td>
                        <td>{{ $square->name }}</td>
                    </tr>
                    <tr>
                        <td>Criação</td>
                        <td>{{ $square->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
table {
    width: 100%;
}
table td {
    padding: 20px 10px;
    border: 1px solid #666666;
}
</style>
