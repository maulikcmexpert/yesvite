<style>
    .accordion-button {
        background-color: white;
        color: #333;
        border: 1px solid #ddd;
    }


    .accordion-button:hover {
        background-color: white;
    }


    .accordion-button:not(.collapsed) {
        background-color: white;
    }
    </style>
    <div class="container my-5">
        <h2 class="text-center mb-4">FAQ</h2>
        <div class="accordion faqaccordion" id="faqAccordion">
            @foreach($faqs as $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading{{ $loop->index }}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $loop->index }}" aria-expanded="true" aria-controls="faqCollapse{{ $loop->index }}">
                            {!! $faq->question !!}
                        </button>
                    </h2>
                    <div id="faqCollapse{{ $loop->index }}" class="accordion-collapse collapse " aria-labelledby="faqHeading{{ $loop->index }}" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {!! $faq->answer !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
