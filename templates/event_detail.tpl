<!--
events.tpl

Author : Christophe Rast, Noémie Sandoz and Andréane Mercier
php/smarty: Flavien Knuchel

Date : 14.6.2013
Description : template of the events page

-->

<section id="event_detail">
    <section id="current_event">

        {if isset($inscriptionStatus) && $inscriptionStatus}
            <a href="inscription.php">Participate</a>
        {/if}
        <article class="desc_event">
            <!-- smarty variables existence test -->
            {if isset($actualEvent) and isset($actualEventLocation)}
                {if !empty($actualEvent) and !empty($actualEventLocation)}
                    <!-- loop through the events array -->
                        <!-- test to display the first event (last of the array) -->
                                <h1>TEDxLausanne No{$actualEvent->getNo()}</h1>
                                <h2>{$actualEvent->getMainTopic()}</h2>
                                <article class="EventAdress">
                                    <h2>Adress</h2>
                                    <p>{$actualEventLocation->getAddress()}</p>
                                    <p>{$actualEventLocation->getName()}</p>
                                    <p>{$actualEventLocation->getCity()}</p>
                                    <p>{$actualEventLocation->getCountry()}</p>
                                </article>
                                <p class="date">{$actualEvent->getStartingDate()}</p>
                                <p>{$actualEvent->getDescription()}</p>
                {else}
                    <!--  error message ifno event is scheduled -->
                    <p>No event scheduled</p>
                {/if}
            {else}
                <!-- error message if no variable set -->
                <p class="error_msg">Error - Event couldn't be found</p>
            {/if}

        </article>

        <article class="programme_event">
            <!-- smarty variables existence test -->
            <h2>Slots</h2>
            {if isset($slotsAndSpeakers)}
                {if !empty($slotsAndSpeakers)}
                    <!-- loop through the slots array and display them-->
                    {section loop=$slotsAndSpeakers name=slot}
                       <ol class="slot_event">
                           {if !is_null($slotsAndSpeakers[slot].slot)}
                                <li><h3>Slot {$slotsAndSpeakers[slot].slot->getNo()}</h3></li>
                                <li>{$slotsAndSpeakers[slot].slot->getStartingTime()} - {$slotsAndSpeakers[slot].slot->getEndingTime()}</li>
                                <li>Live presentation : </li>
                               <ol>

                                        {if !empty($slotsAndSpeakers[slot].speakers)}
                                            {section name=speaker loop=$slotsAndSpeakers[slot].speakers}
                                            <li><a href='speaker_profil.php?No={$slotsAndSpeakers[slot].speakers[speaker]->getNo()}'>
                                                {$slotsAndSpeakers[slot].speakers[speaker]->getFirstName()} {$slotsAndSpeakers[slot].speakers[speaker]->getName()}
                                                </a></li>
                                            {/section}
                                        {/if}

                               </ol>
                            {/if}
                        </ol>
                    {/section}
                {else}
                    <!-- displayed when there is no slot -->
                    <p>No slot scheduled</p>
                {/if}
            {else}
                <!-- display of an error message if the variables aren't set -->
                <p class="error_msg">Error - No slots have been found</p>
            {/if}
        </article>
    </section>

                    

</section>