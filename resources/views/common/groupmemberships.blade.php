<table class="table table-dark table-striped table-bordered">
    <tr>
        <th>Gruppekategori i Canvas</th>
        <th>Gruppemuligheter i dataporten</th>
    </tr>
    @foreach($groupCategories as $groupCategory)
        <tr>
            <td>{{ $groupCategory->name }} </td>
            <td>
                <ul>
                @foreach($groups as $dataportenGroup)
                    <li>{{ $dataportenGroup->displayName . "(" . $dataportenGroup->membership->basic . ")" }}</li>
                @endforeach
                </ul>
            </td>
        </tr>
    @endforeach
</table>
