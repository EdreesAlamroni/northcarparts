@props(['colspan', 'links'])

<tfoot>
    <tr>
        <td colspan="{{ $colspan ?? '' }}">{!! $links !!}</td>
    </tr>
</tfoot>
