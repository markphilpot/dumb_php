<div class="article">
<h2><span>Information Request</span></h2>
<p>Are you an incoming freshman considering membership in the marching band?  
We'd like to get to know you better by asking you to complete the form below.</p>
<p>{$response}</p>
<form {$FormData.attributes}>
<table width="50%" cellspacing="1" cellpadding="3" border="0">
   <tr>
      <th class="form_header">{$FormData.name.label}</th>
      <td>{$FormData.name.html}</td>
   </tr>
   <tr>
      <th class="form_header">{$FormData.address.label}<br />{$FormData.city.label}, {$FormData.state.label} {$FormData.zip.label}</th>
      <td>{$FormData.address.html}<br />{$FormData.city.html}, {$FormData.state.html} {$FormData.zip.html}</td>
   </tr>
   <tr>
      <th class="form_header">{$FormData.email.label}</th>
      <td>{$FormData.email.html}</td>
   </tr>
   <tr>
      <th class="form_header">{$FormData.phone.label}</th>
      <td>{$FormData.phone.html}</td>
   </tr>
   <tr>
      <th class="form_header">{$FormData.instrument.label}</th>
      <td>{$FormData.instrument.html}</td>
   </tr>
   <tr>
      <th class="form_header">{$FormData.highschool.label}</th>
      <td>{$FormData.highschool.html}</td>
   </tr>
   <tr>
      <th class="form_header">{$FormData.director.label}</th>
      <td>{$FormData.director.html}</td>
   </tr>
   <tr>
      <th class="form_header">{$FormData.graduation.label}</th>
      <td>{$FormData.graduation.html}</td>
   </tr>
   <tr>
      <th class="form_header">{$FormData.major.label}</th>
      <td>{$FormData.major.html}</td>
   </tr>
   <tr>
      <th class="form_header">{$FormData.size.label}</th>
      <td>{$FormData.size.html}</td>
   </tr>
   <tr>
      <th class="form_header">{$FormData.dukeID.label}</th>
      <td>{$FormData.dukeID.html}</td>
   </tr>
<!--   <tr>
      <th class="form_header">{$FormData.image.label}<br />(Optional)</th>
      <td>{$FormData.image.html}</td>
   </tr> -->
   <tr>
      <th class="form_header">{$FormData.hsexp.label}</th>
      <td>{$FormData.hsexp.html}</td>
   </tr>
   <tr>
      <th class="form_header">{$FormData.questions.label}</th>
      <td>{$FormData.questions.html}</td>
   </tr>
   <tr>
      <th><br /></th>
      <td>{$FormData.Submit.html}&nbsp;{$FormData.Clear.html}</td>
   </tr>
   <tr>
      <th><br /></th>
      <td><div id="form_result"></div></td>
   </tr>
</table>
</form>
</div> <!-- end article -->