<!--
Latest Tickets Component
Admin Dashboard - Latest Tickets/Chat Messages
-->
<div class="card card-flush">
	<div class="card-header pt-5">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label fw-bold text-gray-900">Tiket/Chat Terbaru</span>
			<span class="text-gray-500 mt-1 fw-semibold fs-6">Pesan terbaru dari peserta</span>
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
						<th>Status</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($latestMessages ?? [] as $message)
						@php
							$user = $message->user ?? null;
							$profile = $user?->profile ?? null;
							$conversation = $message->conversation ?? null;
						@endphp
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="symbol symbol-40px me-3">
										@if($profile && $profile->avatar)
											<img src="{{ asset('storage/' . $profile->avatar) }}" alt="{{ $user->name ?? 'User' }}" />
										@else
											<img src="{{ asset('metronic_html_v8.2.9_demo1/demo1/assets/media/avatars/300-1.jpg') }}" alt="{{ $user->name ?? 'User' }}" />
										@endif
									</div>
									<div class="d-flex flex-column">
										<span class="text-gray-900 fw-bold">{{ $user->name ?? 'Unknown User' }}</span>
										<span class="text-gray-500 fs-7">
											@if($user)
												{{ ucfirst($user->role ?? 'Peserta') }}
											@else
												Sistem
											@endif
										</span>
									</div>
								</div>
							</td>
							<td>
								<span class="text-gray-900 fw-semibold">
									{{ Str::limit($message->body ?? 'No message', 50) }}
								</span>
								@if($conversation && $conversation->title)
									<span class="text-gray-500 fs-7 d-block">{{ $conversation->title }}</span>
								@endif
							</td>
							<td>
								<span class="text-gray-500 fs-7">{{ $message->created_at?->format('d M Y') ?? '-' }}</span>
								<span class="text-gray-500 fs-7 d-block">{{ $message->created_at?->format('H:i') ?? '-' }}</span>
							</td>
							<td>
								<span class="badge badge-light-warning">Open</span>
							</td>
							<td>
								<a href="#" class="btn btn-sm btn-primary" onclick="viewTicket({{ $message->conversation_id ?? $message->id ?? 0 }})">
									Lihat
								</a>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="5" class="text-center py-10">
								<div class="empty-state">
									<div class="icon mb-5">
										<i class="ki-duotone ki-message-text-2 fs-1 text-gray-400">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
										</i>
									</div>
									<div class="text text-gray-500">Tidak ada tiket/chat terbaru</div>
								</div>
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>

