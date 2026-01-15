<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->formatted_receipt_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            padding: 20px;
            background: white;
            color: #000;
        }

        .receipt {
            max-width: 400px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 12px;
            line-height: 1.4;
        }

        .receipt-number {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #000;
        }

        .info-section {
            margin-bottom: 15px;
            font-size: 13px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 15px 0;
        }

        .items-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .items-table th {
            text-align: left;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .items-table td {
            padding: 8px 0;
        }

        .items-table .text-right {
            text-align: right;
        }

        .totals {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #000;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .total-row.grand-total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #000;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px dashed #000;
            font-size: 12px;
        }

        .footer p {
            margin-bottom: 5px;
        }

        .customer-copy {
            text-align: center;
            font-size: 10px;
            margin-top: 15px;
            font-weight: bold;
        }

        @media print {
            body {
                padding: 0;
            }

            .receipt {
                border: none;
                max-width: 100%;
            }

            .no-print {
                display: none;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: system-ui, -apple-system, sans-serif;
        }

        .print-button:hover {
            background: #059669;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Print Receipt
    </button>

    <div class="receipt">
        {{-- Header --}}
        <div class="header">
            <div class="company-name">INVENTORYPRO</div>
            <h1>{{ $sale->location->name }}</h1>
            @if($sale->location->address)
                <p>{{ $sale->location->address }}</p>
            @endif
            @if($sale->location->phone)
                <p>Tel: {{ $sale->location->phone }}</p>
            @endif
        </div>

        {{-- Receipt Number --}}
        <div class="receipt-number">
            RECEIPT #{{ $sale->formatted_receipt_number }}
        </div>

        {{-- Sale Information --}}
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Date:</span>
                <span>{{ $sale->sold_at->format('M d, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Time:</span>
                <span>{{ $sale->sold_at->format('h:i:s A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Served by:</span>
                <span>{{ $sale->seller->name }}</span>
            </div>
        </div>

        {{-- Customer Information --}}
        @if($sale->customer_name || $sale->customer_phone)
        <div class="divider"></div>
        <div class="info-section">
            <div style="font-weight: bold; margin-bottom: 8px;">CUSTOMER INFORMATION</div>
            @if($sale->customer_name)
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span>{{ $sale->customer_name }}</span>
            </div>
            @endif
            @if($sale->customer_phone)
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span>{{ $sale->customer_phone }}</span>
            </div>
            @endif
        </div>
        @endif

        {{-- Items --}}
        <div class="divider"></div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>ITEM</th>
                    <th class="text-right">QTY</th>
                    <th class="text-right">PRICE</th>
                    <th class="text-right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight: bold;">{{ $sale->product->name }}</div>
                        <div style="font-size: 11px; color: #666;">SKU: {{ $sale->product->id }}</div>
                    </td>
                    <td class="text-right">{{ $sale->quantity }}</td>
                    <td class="text-right">₦{{ number_format($sale->unit_price, 2) }}</td>
                    <td class="text-right">₦{{ number_format($sale->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>₦{{ number_format($sale->total_amount, 2) }}</span>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>₦{{ number_format($sale->total_amount, 2) }}</span>
            </div>
        </div>

        {{-- Notes --}}
        @if($sale->notes)
        <div class="divider"></div>
        <div class="info-section">
            <div style="font-weight: bold; margin-bottom: 8px;">NOTES</div>
            <p style="font-size: 12px;">{{ $sale->notes }}</p>
        </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            <p style="font-weight: bold; font-size: 14px;">THANK YOU FOR YOUR BUSINESS!</p>
            <p>Please keep this receipt for your records</p>
            <p style="margin-top: 10px; font-size: 10px;">
                This is a computer-generated receipt
            </p>
        </div>

        <div class="customer-copy">
            - CUSTOMER COPY -
        </div>
    </div>

    <script>
        // Auto-print on page load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
