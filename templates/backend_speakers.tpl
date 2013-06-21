<!--
backend_speakers.tpl

Author : Noémie Sandoz and Andréane Mercier
Date : 18.6.2013

Description : backend of the speakers page
-->
<div class="row offset2">
        <section id="bk_speakers">
            <article>
                <h2>Speakers</h2>
                
                <ul>
                    <!-- do a loop on li-->
                    <li>
                        {$speaker_name} <!-- ATTENTION ajouter boutton modifier et supprimer-->
                    </li>
                </ul>
                    
            </article>
        </section>
        <section id="add_speaker">
            <article>
                <h2>Add a speaker</h2>
                <form method="POST" action="backend_speakers.php">
                    <input type="text" name="Lastname" placeholder="Your name" autofocus required>
                    <input type="text" name="Firstname" placeholder="Your firstname" required>
                    <label for="Date">Date of birth:</label>
                    <input type="date" name="Date" required>
                    <input type="text" name="Adress" placeholder="Your adress" required>
                    <input type="text" name="Town" placeholder="Your town" required>
                    <input type="text" name="Country" placeholder="Your country" required>
                    <input type="tel" name="Phone" placeholder="Your phone number" required>
                    <input type="email" name="Email" placeholder="Your email" required autocomplete>
                    <input type="text" name="Description" placeholder="Description" required>
                 </form>
            </article>
        </section>
</div>

