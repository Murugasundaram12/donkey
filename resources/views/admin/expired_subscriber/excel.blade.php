<table>
    <thead>
        <tr>
            <th>S.No</th>
<th>Joined Date</th>
                                            <th>Subscriber ID</th>
                                            <th>Created By</th>
                                            <th>Name</th>
            <th>Location</th>
            <th>Pincode</th>
            <th>Account Type</th>
            <th>Mobile</th>
            <th>Expiry Date</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($expiredSubscribers as $subscriber)
            {{-- {{ $subscriber->driver }} --}}
            <tr>
                <td>{{ $loop->iteration }}</td>
            <td>{{ $subscriber->joined_date }}</td>
            <td>{{ $subscriber->subscriberId }}</td>
            <td>{{ $id[0] }}</td>
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
                    {{ !empty($subscriberPins) ? implode(', ', $subscriberPins) : '-' }}
                </td>
                <td>{{ $subscriber->account_type ? $subscriber->account_type : 'N/A' }}</td>
                <td>{{ $subscriber->mobile }}</td>
                <td>{{ $subscriber->expiryDate->format('d-m-Y') }}</td>
        @endforeach
    </tbody>
</table>
