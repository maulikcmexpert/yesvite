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
    .faqaccordion h2{
        margin-bottom: 0px !important;
    }

    .faqaccordion .accordion-item{
        margin-bottom: 30px;
        border: none !important;
    }

    .accordion-button{
        border-radius: 15px 15px 0px 0px !important;
    }

    .accordion-body{
        border: 1px solid #dee2e6;
        border-radius: 0px 0px 15px 15px !important;
        border-top: none !important;
    }

    .accordion-button P{
        font-size: 20px;
        font-weight: 600;
        color: var(--headingColor);
        line-height: 32px;
        margin-bottom: 0;
    }
    .accordion-button:focus{
        box-shadow: none !important;
    }

    </style>
    <div class="container my-5">
        <h2 class="text-center mb-4">FAQ</h2>
        <div class="accordion faqaccordion" id="faqAccordion">
            @foreach($faqs as $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading{{ $loop->index }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $loop->index }}" aria-expanded="true" aria-controls="faqCollapse{{ $loop->index }}">
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
