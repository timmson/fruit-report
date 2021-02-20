<table cellpadding="5" width="100%">
	<tr>
   {section name=i loop=$deps}
    <td align="center">
        <a {if $deps[i].name eq $dep}
		            class="cur" href="#"
             {else}
                 href="?dep={$deps[i].name}"
   			      {/if}>
         <br/>
        {$deps[i].descr}
         </a>
		</td>
	{/section}
	</tr>
</table>
