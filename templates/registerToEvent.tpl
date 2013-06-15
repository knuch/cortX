<!--
registerToEvent.tpl

Author : Christophe Rast

Date : 15.6.2013
Description : template of the registration to an event

-->
<section id="registerToEvent">
        <h2>I don't have an account yet</h2>
        <h3>My coordonates</h3>
        <form>
            <div id="coordonees">
                <input type="text" name="Lastname" placeholder="Your name" autofocus required>
                <input type="text" name="Firstname" placeholder="Your firstname" required>
                <label for="Date">Date of birth:</label>
                <input type="date" name="Date" required>
                <input type="text" name="Adress" placeholder="Your adress" required>
                <input type="text" name="Town" placeholder="Your town" required>
                <input type="email" name="Email" placeholder="Your email" required autocomplete>
                <input type="tel" name="Phone" placeholder="Your phone number" required>
                <input type="password" name="Password" placeholder="Choose a password" required>
                <input type="password" name="Password" placeholder="Choose a password" required>
                <input type="password" name="ConfirmPassword" placeholder="Confirm your password" required>
            </div>
            <div id="motivation">
                <label for="motivation">Motivation</label>
                <input type="text" name="Motivation" placeholder="Why should we choose you and not someone else?" required>
                <label for="Keyword" title="Describe your interests in 3 words">Keywords:</label>
                <input type="text" name="Keyword1" placeholder="Keyword" required>
                <input type="text" name="Keyword2" placeholder="Keyword" required>
                <input type="text" name="Keyword3" placeholder="Keyword" required>
                <input type="submit" name="Save" value="Save" alt="Save and edit later">
                <input type="submit" name="Send" value="Send" alt="Submit your registration request">
            </div>
        </form>
</section>