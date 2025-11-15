<!--
Latest Messages Component
Instructor Dashboard - Latest Messages from Students
-->
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Chat Terbaru dari Peserta</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $unread_messages_count }} pesan belum dibaca</span>
		</h3>
	</div>
	<div class="card-body pt-0">
		<div class="table-responsive">
			<table class="table table-dashboard table-row-dashed align-middle gs-0 gy-4">
				<thead>
					<tr>
						<th>Pengirim</th>
						<th>Pesan</th>
						<th>Tanggal</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($latest_messages ?? [] as $message)
					<tr>
						<td>
							<div class="d-flex align-items-center">
								<div class="symbol symbol-40px me-3">
									<img src="{{ $message->sender_avatar }}" alt="{{ $message->sender_name }}" />
								</div>
								<div class="d-flex flex-column">
									<span class="text-gray-900 fw-bold">{{ $message->sender_name }}</span>
									<span class="text-gray-500 fs-7">{{ $message->conversation_title ?? 'Chat' }}</span>
								</div>
							</div>
						</td>
						<td>
							<span class="text-gray-900 fw-semibold">{{ $message->message_preview }}</span>
							<span class="text-gray-500 fs-7 d-block">{{ $message->message_subject }}</span>
						</td>
						<td>
							<span class="text-gray-500 fs-7">{{ $message->message_date }}</span>
							<span class="text-gray-500 fs-7 d-block">{{ $message->message_time }}</span>
						</td>
						<td>
							@if(isset($message->conversation_id))
								<a href="{{ route('instructor.chat.show', $message->conversation_id) }}" class="btn btn-sm btn-primary">
									Lihat Chat
								</a>
							@else
								<a href="{{ route('instructor.messages') }}" class="btn btn-sm btn-primary">
									Lihat
								</a>
							@endif
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="4" class="text-center">
							<div class="empty-state">
								<div class="text">Tidak ada pesan terbaru</div>
							</div>
						</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<!-- If no messages -->
		<div class="empty-state d-none">
			<div class="icon">
				<i class="ki-duotone ki-message-text-2 fs-1">
					<span class="path1"></span>
					<span class="path2"></span>
					<span class="path3"></span>
				</i>
			</div>
			<div class="text">Tidak ada pesan terbaru</div>
		</div>
	</div>
</div>

