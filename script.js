document.addEventListener('DOMContentLoaded', function() {
    updateList('waiting');
    updateList('processing');

    document.getElementById('enqueue').addEventListener('click', function() {
        const item = prompt("Enter item to enqueue:");
        if (item) {
            enqueueItem(item);
        }
    });

    document.getElementById('dequeue').addEventListener('click', function() {
        dequeueItem();
    });

    // Initial load and periodically check for updates
    updateAllLists();
    setInterval(updateAllLists, 5000); // Poll every 5 seconds
});

function enqueueItem(item) {
    fetch(`enqueue.php?item=${encodeURIComponent(item)}`)
        .then(() => updateList('waiting'))
        .catch(error => console.error('Error:', error));
}

function updateList(status) {
    fetch(`list.php?status=${status}`)
        .then(response => response.json())
        .then(data => {
            const listElement = document.getElementById(`${status}List`);
            listElement.innerHTML = ''; // Clear existing items
            data.forEach((item, index) => {
                const itemElement = document.createElement('li');
                itemElement.textContent = item.ClientData + " " + item.EnqueueTime; // Display item data
                if (status == 'waiting') {
                    // Only attach click listeners to items in the waiting list
                    itemElement.addEventListener('click', function() {
                        dequeueItem(); // Call dequeueItem when an item is clicked
                    });
                }
                listElement.appendChild(itemElement);
            });
        })
        .catch(error => console.error('Error:', error));
}

function dequeueItem() {
    fetch('dequeue.php')
        .then(() => {
            updateList('waiting'); // Refresh waiting list
            updateList('processing'); // Refresh processing list
        })
        .catch(error => console.error('Error:', error));
}

function updateAllLists() {
    updateList('waiting');
    updateList('processing');
}
