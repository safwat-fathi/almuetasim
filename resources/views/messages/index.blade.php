<x-layouts.admin>

@section('title', 'الرسائل الواردة')

<div class="drawer lg:drawer-open">
    <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
    <!-- Main Content -->
    <div class="drawer-content flex flex-col">
        <!-- Page Content -->
        <div class="flex-1 p-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">الرسائل الواردة</h1>
                    <span class="badge badge-lg badge-primary">{{ $messages->total() }} رسالة</span>
                </div>

                @if($messages->isEmpty())
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <h3 class="text-xl font-semibold mb-2">لا توجد رسائل</h3>
                        <p class="text-gray-600">لا توجد رسائل واردة حالياً</p>
                    </div>
                @else
                    <div class="overflow-x-auto bg-white rounded-lg shadow">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الهاتف</th>
                                    <th>الرسالة</th>
                                    <th>تاريخ الإرسال</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($messages as $message)
                                    <tr class="{{ $message->read ? '' : 'bg-blue-50' }}">
                                        <td>{{ $message->name }}</td>
                                        <td>{{ $message->email }}</td>
                                        <td>{{ $message->phone ?? 'غير متوفر' }}</td>
                                        <td class="max-w-xs truncate">{{ Str::limit($message->message, 50) }}</td>
                                        <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @if($message->read)
                                                <span class="badge badge-success">مقروءة</span>
                                            @else
                                                <span class="badge badge-warning">جديدة</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-sm btn-primary">
                                                عرض
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $messages->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

</x-layouts.admin>