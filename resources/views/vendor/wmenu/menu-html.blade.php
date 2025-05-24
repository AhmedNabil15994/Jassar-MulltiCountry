<?php
$currentUrl = url()->current();
?>
@inject('pages', 'Modules\Page\Entities\Page')
@inject('categories', 'Modules\Catalog\Entities\Category')
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link href="/vendor/tocaan-menu/style.css" rel="stylesheet">
<script src="/admin/assets/global/plugins/category-tree/tree.js?v=7.3.9" type="text/javascript"></script>
<link rel="stylesheet" href="/admin/assets/global/plugins/category-tree/tree.css?v=7.3.9">

<div id="hwpwrap">
	<div class="custom-wp-admin wp-admin wp-core-ui js   menu-max-depth-0 nav-menus-php auto-fold admin-bar">
		<div id="wpwrap">
			<div id="wpcontent">
				<div id="wpbody">
					<div id="wpbody-content">

						<div class="wrap">

							<div class="manage-menus">
								<form method="get" action="{{ $currentUrl }}">
									<label for="menu" class="selected-menu">Select the menu you want to edit:</label>

									{!! Menu::select('menu', $menulist) !!}

									<span class="submit-btn">
										<input type="submit" class="button-secondary" value="Choose">
									</span>
									<span class="add-new-menu-action"> or <a href="{{ $currentUrl }}?action=edit&menu=0">Create new menu</a>. </span>
								</form>
							</div>
							<div id="nav-menus-frame">

								@if(request()->has('menu')  && !empty(request()->input("menu")))
								<div id="menu-settings-column" class="metabox-holder">

									<div class="clear"></div>

									<div id="side-sortables" class="accordion-container">
										<ul class="outer-border">
											@foreach(config('menu.add_items_forms',[]) as $type => $view)
												<li class="control-section accordion-section add-page" id="add-page">
													@php $item = null;@endphp
													<form action="{{route('dashboard.headermenus.createorupdate')}}" 
													class="nav-menu-meta createitemForm" 
													data-action="create" 
													method="post"
													enctype="multipart/form-data">
														@include($view['view_path'],compact('type','view'))
													</form>
												</li>
											@endforeach
										</ul>
									</div>
								</div>
								@endif
								<div id="menu-management-liquid">
									<div id="menu-management">
										{{-- <form id="update-nav-menu" action="" method="post" enctype="multipart/form-data"> --}}
										<div class="menu-edit ">
											<div id="nav-menu-header">
												<div class="major-publishing-actions">
													<label class="menu-name-label howto open-label" for="menu-name"> <span>Name</span>
														<input name="menu-name" id="menu-name" type="text" class="menu-name regular-text menu-item-textbox" title="Enter menu name" value="@if(isset($indmenu)){{$indmenu->name}}@endif">
														<input type="hidden" id="idmenu" value="@if(isset($indmenu)){{$indmenu->id}}@endif" />
													</label>

													@if(request()->has('action'))
													<div class="publishing-action">
														<a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
													</div>
													@elseif(request()->has("menu"))
													<div class="publishing-action">
														<a onclick="getmenus()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Save menu</a>
														<span class="spinner" id="spincustomu2"></span>
													</div>

													@else
													<div class="publishing-action">
														<a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
													</div>
													@endif
												</div>
											</div>
											<div id="post-body">
												<div id="post-body-content">

													@if(request()->has("menu"))
													<h3>Menu Structure</h3>
													<div class="drag-instructions post-body-plain" style="">
														<p>
															Place each item in the order you prefer. Click on the arrow to the right of the item to display more configuration options.
														</p>
													</div>

													@else
													<h3>Menu Creation</h3>
													<div class="drag-instructions post-body-plain" style="">
														<p>
															Please enter the name and select "Create menu" button
														</p>
													</div>
													@endif

													<ul class="menu ui-sortable" id="menu-to-edit">
														@if(isset($menus))
														@foreach($menus as $m)

															@php $item = $m;@endphp
															<li id="menu-item-{{$m->id}}" class="menu-item menu-item-depth-{{$m->depth}} menu-item-page menu-item-edit-inactive pending" style="display: list-item;">
																
																<form action="{{route('dashboard.headermenus.createorupdate',$item->id)}}"
																	data-action="update" 
																	class="nav-menu-meta createitemForm" 
																	method="post"
																	enctype="multipart/form-data">
																	<dl class="menu-item-bar">
																		<dt class="menu-item-handle">
																			<span class="item-title"> 
																				<span class="menu-item-title"> 
																					<span id="menutitletemp_{{$m->id}}">{{$m->type == 'category' ? $m->itemable->title : $m->label}}</span> 
																					<span style="color: transparent;">|{{$m->id}}|</span> 
																				</span> 
																				<span class="is-submenu" style="@if($m->depth==0)display: none;@endif">Subelement</span>
																			</span>
																			<span class="item-controls"> 
																				<span class="item-type">{{str_replace("_"," ",$m->type)}}</span>
																				<span class="item-order hide-if-js"> 
																					<a href="{{ $currentUrl }}?action=move-up-menu-item&menu-item={{$m->id}}&_wpnonce=8b3eb7ac44" class="item-move-up">
																						<abbr title="Move Up">↑</abbr>
																					</a> | 
																					<a href="{{ $currentUrl }}?action=move-down-menu-item&menu-item={{$m->id}}&_wpnonce=8b3eb7ac44" class="item-move-down">
																						<abbr title="Move Down">↓</abbr>
																					</a> 
																				</span>
																				<a class="item-edit" onclick="toggleEditMenu('{{$m->id}}')"> </a>
																			</span>
																		</dt>
																	</dl>

																	<div class="menu-item-settings" id="menu-item-settings-{{$m->id}}">
																		
																		@include("wmenu::components.additems.forms.{$m->type}",['type' => $m->type])

																		<p class="field-move hide-if-no-js description description-wide">
																			<label> <span>Move</span> <a href="{{ $currentUrl }}" class="menus-move-up" style="display: none;">Move up</a> <a href="{{ $currentUrl }}" class="menus-move-down" title="Mover uno abajo" style="display: inline;">Move Down</a> <a href="{{ $currentUrl }}" class="menus-move-left" style="display: none;"></a> <a href="{{ $currentUrl }}" class="menus-move-right" style="display: none;"></a> <a href="{{ $currentUrl }}" class="menus-move-top" style="display: none;">Top</a> </label>
																		</p>

																		<div class="menu-item-actions description-wide submitbox">

																			<a class="item-delete submitdelete deletion" id="delete-{{$m->id}}" onclick="deleteitem('{{$m->id}}')">Delete</a>
																			<span class="meta-sep hide-if-no-js"> | </span>
																			<button type="submit" class="button-primary updatemenu">
																				<span class="spinner"></span>
																				Update item
																			</button>

																		</div>

																	</div>
																	<ul class="menu-item-transport"></ul>
																</form>
															</li>
														@endforeach
														@endif
													</ul>
													<div class="menu-settings">

													</div>
												</div>
											</div>
											<div id="nav-menu-footer">
												<div class="major-publishing-actions">

													@if(request()->has('action'))
													<div class="publishing-action">
														<a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
													</div>
													@elseif(request()->has("menu"))
													<span class="delete-action"> <a class="submitdelete deletion menu-delete" onclick="deletemenu()" href="javascript:void(9)">Delete menu</a> </span>
													<div class="publishing-action">

														<a onclick="getmenus()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Save menu</a>
														<span class="spinner" id="spincustomu2"></span>
													</div>

													@else
													<div class="publishing-action">
														<a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
													</div>
													@endif
												</div>
											</div>
										</div>
										{{-- </form> --}}
									</div>
								</div>
							</div>
						</div>

						<div class="clear"></div>
					</div>

					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="clear"></div>
		</div>
	</div>
</div>
