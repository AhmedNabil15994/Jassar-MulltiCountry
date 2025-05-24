@if ($paginator->lastPage() > 1)
    <nav aria-label="...">
        <ul class="pagination pagination-sm">

            <li class="page-item previous {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage() - 1) }}">
                    <i class="ti-angle-left"></i>
                </a>
            </li>

            @php 
                $showPagesNumbers = 4;
                if($paginator->lastPage() > $showPagesNumbers){
                    
                    if((($paginator->lastPage() - $paginator->currentPage()) > $showPagesNumbers)){

                        $startCounter = $paginator->currentPage();
                        $endCounter = $startCounter + $showPagesNumbers;
                    }else{

                        $startCounter = $paginator->lastPage() - $showPagesNumbers;
                        $endCounter = $paginator->lastPage();
                    }
                }else{
                    $startCounter = 1;
                    $endCounter = $paginator->lastPage();
                }
                if($endCounter != $paginator->lastPage() || (($paginator->lastPage() - $paginator->currentPage()) > ($showPagesNumbers/2))){

                    for ($i = 2; $startCounter > 1 && $i > 0; $i--){
                        $startCounter --;
                        $endCounter -- ;
                    }
                }
            @endphp
            @for ($i = $startCounter; $i <= $endCounter; $i++)
                <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
        
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage() + 1) }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
            <li class="page-item next  {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage() + 1) }}"><i class="ti-angle-right"></i>
                </a></li>
        </ul>
    </nav>
@endif
