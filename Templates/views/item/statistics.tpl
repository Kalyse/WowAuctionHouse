<script type="text/javascript" src="js/search.js"></script>
<script type="text/javascript>">
setCookie('region','{{ region }}');
setCookie('realm','{{ realm }}');
</script>
<h1>Statistics for {{ item.name }}</h1>
<div>{{ region | upper }}/{{ realm | capitalize }}: {{ house | capitalize }} <form><input type="hidden" name="region" value="{{ region }}"/><input type="hidden" name="realm" value="{{ realm }}"/><input type="hidden" name="house" value="{{ house }}"/><input type="text" name="itemname" /></form></div>

<img src="?do=item|chart&amp;item={{item.id}}&amp;region={{region}}&amp;realm={{realm}}&amp;house={{house}}" alt="item chart" />
