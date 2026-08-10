@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="bg-brand-off-white min-h-screen py-12">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8">
        
        <h1 class="text-3xl font-heading font-extrabold uppercase tracking-tight text-brand-dark mb-8">Checkout</h1>
        
        <div class="flex flex-col lg:flex-row gap-12" x-data="checkoutForm()">
            
            {{-- LEFT: Forms --}}
            <div class="flex-1 space-y-8">
                
                {{-- Shipping Address --}}
                <div class="bg-white border border-brand-border rounded-lg p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-brand-dark mb-6">1. Shipping Address</h2>
                    
                    @if($addresses->count() > 0)
                        <div class="mb-6 space-y-4">
                            <p class="text-sm font-semibold text-brand-muted">Select an existing address:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($addresses as $addr)
                                    <label class="border border-brand-border rounded p-4 cursor-pointer hover:border-brand-text transition-colors flex items-start gap-3"
                                           :class="{'border-brand-text bg-brand-light': selectedAddressId == {{ $addr->id }}}">
                                        <input type="radio" x-model="selectedAddressId" value="{{ $addr->id }}" class="mt-1 text-brand-text focus:ring-brand-text">
                                        <div class="text-sm">
                                            <p class="font-bold text-brand-dark">{{ $addr->name }} <span class="text-xs uppercase bg-brand-off-white px-2 py-0.5 rounded ml-2">{{ $addr->type }}</span></p>
                                            <p class="text-brand-muted">{{ $addr->street }}</p>
                                            <p class="text-brand-muted">{{ $addr->city }}, {{ $addr->state }} {{ $addr->pincode }}</p>
                                            <p class="text-brand-muted mt-1">Phone: {{ $addr->phone }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <button @click="selectedAddressId = null; showNewAddress = true" class="text-sm text-brand-text font-semibold hover:underline mt-4 inline-block">
                                + Add a new address
                            </button>
                        </div>
                    @else
                        <div x-init="showNewAddress = true"></div>
                    @endif

                    <div x-show="showNewAddress || '{{ $addresses->count() }}' == '0'" x-transition class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-brand-muted uppercase tracking-wider mb-2">Full Name</label>
                                <input type="text" x-model="form.name" class="input-field" placeholder="John Doe">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-brand-muted uppercase tracking-wider mb-2">Phone Number</label>
                                <input type="text" x-model="form.phone" class="input-field" placeholder="+91 9876543210">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-brand-muted uppercase tracking-wider mb-2">Street Address</label>
                            <input type="text" x-model="form.street" class="input-field" placeholder="123 Main St, Apt 4B">
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-brand-muted uppercase tracking-wider mb-2">City</label>
                                <input type="text" x-model="form.city" class="input-field" placeholder="Mumbai">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-brand-muted uppercase tracking-wider mb-2">State</label>
                                <input type="text" x-model="form.state" class="input-field" placeholder="Maharashtra">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-xs font-bold text-brand-muted uppercase tracking-wider mb-2">PIN Code</label>
                                <input type="text" x-model="form.pincode" class="input-field" placeholder="400001">
                            </div>
                        </div>
                        
                        @if($addresses->count() > 0)
                            <button @click="showNewAddress = false; selectedAddressId = {{ $addresses->first()->id }}" class="text-sm text-brand-muted hover:text-brand-text font-semibold hover:underline mt-2 inline-block">
                                Cancel new address
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white border border-brand-border rounded-lg p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-brand-dark mb-6">2. Payment Method</h2>
                    <div class="space-y-4">
                        <label class="border border-brand-border rounded p-4 cursor-pointer hover:border-brand-text transition-colors flex items-center justify-between"
                               :class="{'border-brand-text bg-brand-light': form.payment_method === 'razorpay'}">
                            <div class="flex items-center gap-3">
                                <input type="radio" x-model="form.payment_method" value="razorpay" class="text-brand-text focus:ring-brand-text h-4 w-4">
                                <span class="font-bold text-brand-dark">Pay Online (UPI, Cards, NetBanking)</span>
                            </div>
                            <div class="flex gap-2">
                                <span class="bg-gray-200 rounded px-2 py-1 text-[10px] font-bold">RAZORPAY</span>
                            </div>
                        </label>

                        <label class="border border-brand-border rounded p-4 cursor-pointer hover:border-brand-text transition-colors flex items-center justify-between"
                               :class="{'border-brand-text bg-brand-light': form.payment_method === 'cod'}">
                            <div class="flex items-center gap-3">
                                <input type="radio" x-model="form.payment_method" value="cod" class="text-brand-text focus:ring-brand-text h-4 w-4">
                                <span class="font-bold text-brand-dark">Cash on Delivery (COD)</span>
                            </div>
                            <span class="text-brand-muted text-xs font-semibold">Pay at doorstep</span>
                        </label>
                    </div>
                </div>

            </div>

            {{-- RIGHT: Order Summary --}}
            <div class="lg:w-96 shrink-0">
                <div class="bg-white border border-brand-border rounded-lg p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-brand-dark mb-6">Order Summary</h2>
                    
                    <ul class="divide-y divide-brand-border mb-6">
                        @foreach($cart->items as $item)
                        <li class="py-4 flex gap-4">
                            <div class="w-16 h-20 bg-brand-light rounded border border-brand-border overflow-hidden shrink-0">
                                @if($item->variant->product->primaryImage)
                                    <img src="{{ $item->variant->product->primaryImage->url }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <p class="text-sm font-bold text-brand-dark line-clamp-1">{{ $item->variant->product->name }}</p>
                                    <p class="text-xs text-brand-muted mt-1">{{ $item->variant->color }} / {{ $item->variant->size }}</p>
                                </div>
                                <div class="flex justify-between items-end mt-2">
                                    <p class="text-xs text-brand-muted">Qty: {{ $item->quantity }}</p>
                                    <p class="text-sm font-bold">₹{{ number_format(($item->variant->price ?? $item->price_at_time) * $item->quantity) }}</p>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <div class="space-y-3 text-sm mb-6 border-b border-brand-border pb-6">
                        <div class="flex justify-between text-brand-muted">
                            <p>Subtotal</p>
                            <p class="font-semibold text-brand-dark">₹{{ number_format($summary['subtotal']) }}</p>
                        </div>
                        <div class="flex justify-between text-brand-muted">
                            <p>Shipping</p>
                            <p class="font-semibold {{ $summary['shipping'] == 0 ? 'text-green-600' : 'text-brand-dark' }}">
                                {{ $summary['shipping'] == 0 ? 'FREE' : '₹'.number_format($summary['shipping']) }}
                            </p>
                        </div>
                        <div class="flex justify-between text-brand-muted">
                            <p>Tax (Included)</p>
                            <p class="font-semibold text-brand-dark">₹0</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-lg font-bold text-brand-dark mb-8">
                        <p>Total</p>
                        <p>₹{{ number_format($summary['total']) }}</p>
                    </div>

                    <button @click="placeOrder" :disabled="loading" class="btn-primary w-full py-4 text-center disabled:opacity-70 flex justify-center items-center">
                        <span x-show="!loading">Place Order & Pay</span>
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    
                    <p class="text-xs text-brand-muted text-center mt-4">
                        By placing your order, you agree to our <a href="#" class="underline hover:text-brand-text">Terms of Service</a> and <a href="#" class="underline hover:text-brand-text">Privacy Policy</a>.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('checkoutForm', () => ({
            showNewAddress: false,
            selectedAddressId: {{ $addresses->count() > 0 ? $addresses->first()->id : 'null' }},
            form: {
                name: '',
                phone: '',
                street: '',
                city: '',
                state: '',
                pincode: '',
                payment_method: 'razorpay'
            },
            loading: false,

            async placeOrder() {
                // Validation
                if (!this.selectedAddressId && !this.showNewAddress) {
                    alert('Please select an address.'); return;
                }
                if (this.showNewAddress && (!this.form.name || !this.form.phone || !this.form.street || !this.form.city || !this.form.state || !this.form.pincode)) {
                    alert('Please fill all address fields.'); return;
                }

                this.loading = true;

                let payload = { ...this.form };
                if (!this.showNewAddress && this.selectedAddressId) {
                    payload.address_id = this.selectedAddressId;
                }

                try {
                    let res = await fetch('{{ route("frontend.checkout.process") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    });
                    
                    let data = await res.json();
                    
                    if (!data.success) {
                        alert(data.message || 'Error processing order');
                        this.loading = false;
                        return;
                    }

                    if (data.redirect_url) {
                        // COD successful
                        window.location.href = data.redirect_url;
                    } else if (data.razorpay_order_id) {
                        // Razorpay
                        this.openRazorpay(data);
                    }
                    
                } catch (e) {
                    console.error(e);
                    alert('Something went wrong. Please try again.');
                    this.loading = false;
                }
            },

            openRazorpay(data) {
                var options = {
                    "key": "{{ env('RAZORPAY_KEY_ID', 'rzp_test_dummy') }}", 
                    "amount": data.amount,
                    "currency": data.currency,
                    "name": data.name,
                    "description": data.description,
                    "order_id": data.razorpay_order_id,
                    "prefill": {
                        "name": data.prefill.name,
                        "email": data.prefill.email,
                        "contact": data.prefill.contact
                    },
                    "theme": {
                        "color": "#111111" // brand dark
                    },
                    "handler": function (response){
                        // Redirect to callback route via form post
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("frontend.checkout.callback") }}';
                        
                        let csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        form.appendChild(csrf);

                        let sig = document.createElement('input');
                        sig.type = 'hidden';
                        sig.name = 'razorpay_signature';
                        sig.value = response.razorpay_signature;
                        form.appendChild(sig);

                        let pid = document.createElement('input');
                        pid.type = 'hidden';
                        pid.name = 'razorpay_payment_id';
                        pid.value = response.razorpay_payment_id;
                        form.appendChild(pid);

                        let oid = document.createElement('input');
                        oid.type = 'hidden';
                        oid.name = 'razorpay_order_id';
                        oid.value = response.razorpay_order_id;
                        form.appendChild(oid);

                        document.body.appendChild(form);
                        form.submit();
                    }
                };

                // Handle Dummy Test Mode bypass
                if (options.key === 'rzp_test_dummy') {
                    alert('TEST MODE: Simulating successful Razorpay payment!');
                    
                    // Simulate form post
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("frontend.checkout.callback") }}';
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="razorpay_signature" value="dummy_signature">
                        <input type="hidden" name="razorpay_payment_id" value="pay_dummy_${Math.floor(Math.random()*100000)}">
                        <input type="hidden" name="razorpay_order_id" value="${data.razorpay_order_id}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                    return;
                }

                var rzp1 = new Razorpay(options);
                rzp1.on('payment.failed', function (response){
                    alert("Payment Failed: " + response.error.description);
                    this.loading = false;
                }.bind(this));
                
                rzp1.open();
            }
        }));
    });
</script>
@endpush
