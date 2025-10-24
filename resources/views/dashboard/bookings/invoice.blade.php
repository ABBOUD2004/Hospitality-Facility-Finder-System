<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice #{{ $booking->booking_reference }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            min-height: 100vh;
        }

        .invoice-wrapper {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 10px 60px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
        }

        /* Header Section */
        .invoice-header {
            background: linear-gradient(135deg, #F46A06 0%, #FF8C42 100%);
            color: white;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: start;
            position: relative;
            z-index: 1;
        }

        .company-info {
            flex: 1;
        }

        .company-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .logo-circle {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #F46A06;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .company-name {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .company-details {
            font-size: 13px;
            opacity: 0.95;
            line-height: 1.8;
        }

        .company-details i {
            margin-right: 8px;
            width: 16px;
        }

        .invoice-title-section {
            text-align: right;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .invoice-title {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: 2px;
        }

        .invoice-meta {
            font-size: 14px;
            line-height: 2;
        }

        /* Content Section */
        .invoice-body {
            padding: 40px;
        }

        .status-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .status-info h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .status-info p {
            opacity: 0.9;
            font-size: 14px;
        }

        .status-badge {
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .status-pending { background: #ffc107; color: #856404; }
        .status-confirmed { background: #17a2b8; color: white; }
        .status-checked_in { background: #28a745; color: white; }
        .status-completed { background: #6c757d; color: white; }
        .status-cancelled { background: #dc3545; color: white; }

        /* Details Grid */
        .details-section {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .details-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid #F46A06;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .details-card h3 {
            color: #F46A06;
            font-size: 16px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .details-card h3 i {
            font-size: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
        }

        /* Booking Specific Details */
        .booking-specifics {
            background: white;
            border: 2px solid #f0f0f0;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .booking-specifics h3 {
            color: #333;
            font-size: 20px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #F46A06;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .specifics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .specific-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .specific-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .specific-value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        /* Order Items Table */
        .order-items {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .order-items h3 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin: 0;
            font-size: 18px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead {
            background: #f8f9fa;
        }

        .items-table th {
            padding: 15px;
            text-align: left;
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .items-table tbody tr:hover {
            background: #f8f9fa;
        }

        .item-name {
            font-weight: 600;
            color: #333;
        }

        .item-quantity {
            color: #666;
            text-align: center;
        }

        .item-price {
            font-weight: 600;
            color: #F46A06;
            text-align: right;
        }

        /* Pricing Section */
        .pricing-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .pricing-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 15px;
        }

        .pricing-row.subtotal {
            border-bottom: 1px solid #dee2e6;
            color: #666;
        }

        .pricing-row.total {
            margin-top: 15px;
            padding-top: 20px;
            border-top: 3px solid #F46A06;
            font-size: 24px;
            font-weight: 700;
            color: #F46A06;
        }

        .payment-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: white;
            border-radius: 8px;
            font-size: 14px;
        }

        /* Special Requests */
        .special-requests {
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
            border-left: 4px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .special-requests h4 {
            color: #f57c00;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .special-requests p {
            color: #666;
            line-height: 1.6;
        }

        /* Footer */
        .invoice-footer {
            background: #f8f9fa;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .thank-you {
            font-size: 24px;
            font-weight: 600;
            color: #F46A06;
            margin-bottom: 15px;
        }

        .footer-contact {
            color: #666;
            margin-bottom: 20px;
        }

        .footer-note {
            font-size: 12px;
            color: #999;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        /* Action Buttons */
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-print {
            background: linear-gradient(135deg, #F46A06 0%, #FF8C42 100%);
            color: white;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(244, 106, 6, 0.4);
        }

        .btn-download {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-wrapper {
                box-shadow: none;
                border-radius: 0;
            }

            .action-buttons {
                display: none;
            }

            .invoice-header::before {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 20px;
            }

            .invoice-title-section {
                text-align: left;
            }

            .details-section {
                grid-template-columns: 1fr;
            }

            .specifics-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                position: static;
                margin-bottom: 20px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Action Buttons -->
    <div class="action-buttons no-print">
        <button onclick="window.history.back()" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Print
        </button>
        <button onclick="downloadPDF()" class="btn btn-download">
            <i class="fas fa-download"></i> Download PDF
        </button>
    </div>

    <div class="invoice-wrapper">
        <!-- Header -->
        <div class="invoice-header">
            <div class="header-content">
                <div class="company-info">
                    <div class="company-logo">
                        <div class="logo-circle">
                            <i class="fas fa-building"></i>
                        </div>
                        <h1 class="company-name">Your Company</h1>
                    </div>
                    <div class="company-details">
                        <p><i class="fas fa-map-marker-alt"></i> 123 Business Street, Kigali, Rwanda</p>
                        <p><i class="fas fa-phone"></i> +250 788 123 456</p>
                        <p><i class="fas fa-envelope"></i> info@yourcompany.rw</p>
                        <p><i class="fas fa-globe"></i> www.yourcompany.rw</p>
                    </div>
                </div>

                <div class="invoice-title-section">
                    <h2 class="invoice-title">INVOICE</h2>
                    <div class="invoice-meta">
                        <p><strong>Invoice #:</strong> {{ $booking->booking_reference }}</p>
                        <p><strong>Issue Date:</strong> {{ $booking->created_at->format('M d, Y') }}</p>
                        <p><strong>Due Date:</strong> {{ $booking->created_at->addDays(7)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <!-- Status Banner -->
            <div class="status-banner">
                <div class="status-info">
                    <h3><i class="fas fa-info-circle"></i> Booking Status</h3>
                    <p>Current status of your booking</p>
                </div>
                <span class="status-badge status-{{ $booking->status }}">
                    {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                </span>
            </div>

            <!-- Customer & Booking Info -->
            <div class="details-section">
                <div class="details-card">
                    <h3><i class="fas fa-user"></i> Bill To</h3>
                    <div class="detail-row">
                        <span class="detail-label">Name:</span>
                        <span class="detail-value">{{ $booking->guest_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value">{{ $booking->guest_phone }}</span>
                    </div>
                    @if($booking->guest_email)
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $booking->guest_email }}</span>
                    </div>
                    @endif
                    @if($booking->user)
                    <div class="detail-row">
                        <span class="detail-label">Account:</span>
                        <span class="detail-value">{{ $booking->user->name }}</span>
                    </div>
                    @endif
                </div>

                <div class="details-card">
                    <h3><i class="fas fa-clipboard-list"></i> Booking Info</h3>
                    <div class="detail-row">
                        <span class="detail-label">Booking ID:</span>
                        <span class="detail-value">#{{ $booking->id }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Type:</span>
                        <span class="detail-value">{{ ucfirst($booking->booking_type) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Booked On:</span>
                        <span class="detail-value">{{ $booking->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @if($booking->confirmed_at)
                    <div class="detail-row">
                        <span class="detail-label">Confirmed:</span>
                        <span class="detail-value">{{ $booking->confirmed_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Booking Specific Details -->
            <div class="booking-specifics">
                @if($booking->booking_type == 'hotel')
                    <h3><i class="fas fa-hotel"></i> Hotel Booking Details</h3>
                    <div class="specifics-grid">
                        <div class="specific-item">
                            <div class="specific-label">Hotel</div>
                            <div class="specific-value">{{ $booking->facility->name ?? 'N/A' }}</div>
                        </div>
                        <div class="specific-item">
                            <div class="specific-label">Room Type</div>
                            <div class="specific-value">{{ $booking->room->name ?? 'N/A' }}</div>
                        </div>
                        <div class="specific-item">
                            <div class="specific-label">Check-in</div>
                            <div class="specific-value">{{ $booking->check_in_date?->format('M d, Y') }}</div>
                        </div>
                        <div class="specific-item">
                            <div class="specific-label">Check-out</div>
                            <div class="specific-value">{{ $booking->check_out_date?->format('M d, Y') }}</div>
                        </div>
                        <div class="specific-item">
                            <div class="specific-label">Nights</div>
                            <div class="specific-value">{{ $booking->nights }} Night(s)</div>
                        </div>
                        <div class="specific-item">
                            <div class="specific-label">Guests</div>
                            <div class="specific-value">{{ $booking->adults }} Adults, {{ $booking->children }} Children</div>
                        </div>
                    </div>

                @elseif($booking->booking_type == 'transport')
                    <h3><i class="fas fa-car"></i> Transport Booking Details</h3>
                    <div class="specifics-grid">
                        <div class="specific-item">
                            <div class="specific-label">Pickup Location</div>
                            <div class="specific-value">{{ $booking->pickup_location }}</div>
                        </div>
                        <div class="specific-item">
                            <div class="specific-label">Destination</div>
                            <div class="specific-value">{{ $booking->destination }}</div>
                        </div>
                        <div class="specific-item">
                            <div class="specific-label">Date</div>
                            <div class="specific-value">{{ $booking->reservation_date?->format('M d, Y') }}</div>
                        </div>
                        <div class="specific-item">
                            <div class="specific-label">Time</div>
                            <div class="specific-value">{{ $booking->reservation_time }}</div>
                        </div>
                        <div class="specific-item">
                            <div class="specific-label">Passengers</div>
                            <div class="specific-value">{{ $booking->number_of_guests }} Person(s)</div>
                        </div>
                    </div>

                @elseif(in_array($booking->booking_type, ['restaurant', 'coffee']))
                    <h3>
                        <i class="fas fa-{{ $booking->booking_type == 'coffee' ? 'coffee' : 'utensils' }}"></i>
                        {{ ucfirst($booking->booking_type) }} Order Details
                    </h3>
                    <div class="specifics-grid">
                        <div class="specific-item">
                            <div class="specific-label">{{ ucfirst($booking->booking_type) }}</div>
                            <div class="specific-value">{{ $booking->facility->name ?? 'N/A' }}</div>
                        </div>
                        <div class="specific-item">
                            <div class="specific-label">Order Date</div>
                            <div class="specific-value">{{ $booking->reservation_date?->format('M d, Y') ?? $booking->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="specific-item">
                            <div class="specific-label">Order Time</div>
                            <div class="specific-value">{{ $booking->reservation_time ?? $booking->created_at->format('h:i A') }}</div>
                        </div>
                        @if($booking->delivery_address)
                        <div class="specific-item">
                            <div class="specific-label">Delivery Address</div>
                            <div class="specific-value">{{ $booking->delivery_address }}</div>
                        </div>
                        @endif
                    </div>

                    @if($booking->order_items && is_array($booking->order_items))
                    <div class="order-items" style="margin-top: 30px;">
                        <h3><i class="fas fa-list"></i> Ordered Items</h3>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th style="text-align: center;">Quantity</th>
                                    <th style="text-align: right;">Price</th>
                                    <th style="text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->order_items as $item)
                                <tr>
                                    <td class="item-name">{{ $item['name'] ?? 'N/A' }}</td>
                                    <td class="item-quantity">{{ $item['quantity'] ?? 1 }}</td>
                                    <td class="item-price">{{ number_format($item['price'] ?? 0, 0) }} RWF</td>
                                    <td class="item-price">{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0) }} RWF</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                @endif
            </div>

            <!-- Special Requests -->
            @if($booking->special_requests)
            <div class="special-requests">
                <h4><i class="fas fa-sticky-note"></i> Special Requests / Notes</h4>
                <p>{{ $booking->special_requests }}</p>
            </div>
            @endif

            <!-- Pricing Section -->
            <div class="pricing-section">
                <div class="pricing-row subtotal">
                    <span>Subtotal:</span>
                    <span>{{ number_format($booking->total_price ?? $booking->total_price_rwf ?? 0, 0) }} RWF</span>
                </div>

                @if($booking->booking_type == 'hotel' && $booking->nights > 0)
                <div class="pricing-row subtotal">
                    <span>Price per Night:</span>
                    <span>{{ number_format(($booking->total_price ?? 0) / $booking->nights, 0) }} RWF</span>
                </div>
                @endif

                <div class="pricing-row total">
                    <span>TOTAL AMOUNT:</span>
                    <span>{{ number_format($booking->total_price ?? $booking->total_price_rwf ?? 0, 0) }} RWF</span>
                </div>

                <div class="payment-info">
                    <div class="payment-item">
                        <span><i class="fas fa-credit-card"></i> Payment Method:</span>
                        <strong>{{ strtoupper($booking->payment_method ?? 'N/A') }}</strong>
                    </div>
                    <div class="payment-item">
                        <span><i class="fas fa-check-circle"></i> Payment Status:</span>
                        <strong style="color: {{ $booking->payment_status == 'paid' ? '#28a745' : '#ffc107' }};">
                            {{ ucfirst($booking->payment_status ?? 'Pending') }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="thank-you">
                <i class="fas fa-heart" style="color: #F46A06;"></i>
                Thank You for Your Business!
            </div>
            <div class="footer-contact">
                <p>For any questions or concerns regarding this invoice, please contact us:</p>
                <p><strong>Email:</strong> info@yourcompany.rw | <strong>Phone:</strong> +250 788 123 456</p>
            </div>
            <div class="footer-note">
                <p>This is a computer-generated invoice and does not require a physical signature.</p>
                <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
            </div>
        </div>
    </div>

    <script>
        function downloadPDF() {
            window.print();
        }
    </script>
</body>
</html>
