{if isset($this->exception)}
    <h2>{$this->translate('Exception thrown')}:</h2>
    <dl>
        <dt>{$this->translate('File')}:</dt>
        <dd>
            <pre class="prettyprint linenums">{$this->exception->getFile()}:{$this->exception->getLine()}</pre>
        </dd>
        <dt>{$this->translate('Message')}:</dt>
        <dd>
            <pre class="prettyprint linenums">{$this->exception->getMessage()}</pre>
        </dd>
        <dt>{$this->translate('Stack trace')}:</dt>
        <dd>
            <pre class="prettyprint linenums">{$this->exception->getTraceAsString()}</pre>
        </dd>
    </dl>
{/if}

