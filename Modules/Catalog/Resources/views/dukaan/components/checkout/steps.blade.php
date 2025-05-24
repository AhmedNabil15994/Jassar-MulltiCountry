
<div id="form">
    <ul id="progressbar">
        <li v-for="step in checkout_steps" :class="step.status ? 'active' : ''" :id="step.id">
            <strong> @{{step.title}}</strong>
        </li>
    </ul>
</div>