{{ dd($eventList) }}
<x-front.advertise />
<!-- ============= contact-details ============ -->
<section class="contact-details profile-details">
    <div class="container">
        <div class="row">
            <x-front.sidebar :profileData="[]" />
            <x-main_menu.drafts.draft_list :eventDraftdata="$eventDraftdata" />
        </div> 
    </div>     
</section>   