<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        caption {
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <table>
        <caption>Expiring Subscriber's List</caption>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Subscriber ID</th>
                <th>Created By</th>
                <th>Joined Date</th>
                <th>Joined Date</th>
                <th>Name</th>
                <th>Location</th>
                <th>Pincode</th>
                <th>Account Type</th>
                <th>Mobile</th>
                <th>Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0;
            @endphp
            @foreach ($expiredSubscribers as $subscriber)
                    <tr>


                 <td>{{ $loop->iteration }}</td>
                 <td>{{ $subscriber->subscriberId }}</td>
                 <td>{{ $id[0] }}</td>
                 <td>{{ $subscriber->joined_date }}</td>
                        <td>{{ $subscriber->name }}</td>
                        <td>{{ $subscriber->location }}</td>
                        <td>
                            @php
                                $subspin = json_decode($subscriber->pincode, true);
                                if (!is_array($subspin) || empty($subspin)) {
                                    if (!empty($subscriber->pincode)) {
                                        $subspin = explode(',', $subscriber->pincode);
                                    } else {
                                        $subspin = \App\Models\Pincodebasedcategory::where('subscriber_id', $subscriber->id)->pluck('pincode_id')->toArray();
                                    }
                                }
                                $subspin = array_map('intval', array_filter((array)$subspin));
                                $subscriberPins = [];
                                if (!empty($subspin) && !empty($pincode)) {
                                    foreach ($pincode as $pin) {
                                        if (in_array((int)$pin->id, $subspin, true)) {
                                            $subscriberPins[] = $pin->pincode;
                                        }
                                    }
                                }
                            @endphp
                            {!! !empty($subscriberPins) ? implode('<br>', $subscriberPins) : '-' !!}
                        </td>
                        <td>{{ $subscriber->account_type ? $subscriber->account_type : 'N/A' }}</td>
                        <td>{{ $subscriber->mobile }}</td>
                             <td>{{ $subscriber->expiryDate->format('d-m-Y') }}</td>
            @endforeach
        </tbody>
    </table>
</body>

</html>
