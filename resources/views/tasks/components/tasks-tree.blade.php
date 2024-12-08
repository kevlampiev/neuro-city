@forelse($tasks as $el)
    @if(count($el->subTasks)==0)
        <p>@include('tasks.task-record', ['task' => $el])</p>
    @else
        <details id="task-{{$el->id}}">
            <summary>
                @include('tasks.task-record', ['task' => $el])
            </summary>
            <div class="ml-5">
                @include('tasks.subtasks', ['subtasks' =>$el->subTasks])
            </div>
        </details>
    @endif
@empty
    <p>Нет задач для отображения</p>
@endforelse
@section('script')
    <script>
        window.onload = restoreDetails

        function restoreDetails() {
            alert('Details restored')
        }

    </script>

    // let details = document.querySelector('details')
    //     // добавить к каждому клику события клика
    // details.forEach((el) => {
    //     el.addEventListener("click", _ => {
    //         let openedDetails = []
    //         details.forEach(detail => {
    //             if detail.hasAtrribute('open')
    //                 openedDetails.push(details.id)
    //         })
    //         console.log(openedDetails)
    //     })
    // })


@endsection
