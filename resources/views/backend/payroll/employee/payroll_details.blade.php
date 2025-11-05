<tr>
    <td style="font-size: 15px;"> Basic Salary  </td>
    <td class="text-right" style="font-size: 15px;"> {{$salary['basic_salary']}} </td>
</tr>

<tr>
    <td style="font-size: 15px;"> Over Time </td>
    <td class="text-right" style="font-size: 15px;"> {{$salary['overtime_amount']}} </td>
</tr>

<tr>
    <td style="font-size: 15px;"> Today Salary </td>
    <td class="text-right" style="font-size: 15px;"> {{$salary['basic_salary_current_day']}} </td>
</tr>

<tr>
    <td style="font-size: 15px;"> Late Deduction   </td>
    <td class="text-right" style="font-size: 15px;"> {{$salary['late_amount']}} </td>
</tr>

<tr>
    <td style="font-size: 15px;"> Absen Deduction  </td>
    <td class="text-right" style="font-size: 15px;"> {{$salary['total_absen_penalty']}} </td>
</tr>

<tr>
    <td style="font-size: 15px;">  <strong> Total  </strong>  </td>
    <td class="text-right" style="font-size: 15px;"> {{$salary['amount']}} </td>
</tr>

<tr>
    <td style="font-size: 15px;"> <strong> Paid Salary </strong> </td>
    <td class="text-right" style="font-size: 15px;"><strong>  {{$salary['paid_salary']}} </strong> </td>
</tr>

<tr>
    <td style="font-size: 15px;"> <strong>  Advance </strong> </td>
    <td class="text-right" style="font-size: 15px;"> <strong> {{$salary['advance']}} </strong> </td>
</tr>

<tr>
    <td style="font-size: 15px;"> <strong>  Due </strong> </td>
    <td class="text-right" style="font-size: 15px;"> <strong> {{$salary['amount'] - ($salary['advance'] + $salary['paid_salary'])}} </strong> </td>
</tr>
