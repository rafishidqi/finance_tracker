document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");

  // Fetch events dari server
  fetch("page/kalender/get-events.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      // Cek apakah response berisi error
      if (data.error) {
        console.error("Error:", data.error);
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Error found!" + data.error,
        });
        return;
      }

      // Jika tidak ada event
      if (data.message) {
        console.warn(data.message);
        alert(data.message);
        return;
      }

      // Pastikan data events berbentuk array
      const events = Array.isArray(data) ? data : [];

      // Inisialisasi FullCalendar
      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        headerToolbar: {
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth,timeGridWeek,timeGridDay",
        },
        events: events.map((event) => ({
          ...event,
          extendedProps: {
            description: event.description || "No description available",
          },
        })),
        eventContent: function (arg) {
          const title = arg.event.title;
          const description = arg.event.extendedProps.description || "";
          return {
            html: `
              <div style="padding: 5px; text-align: left;">
                <div style="font-weight: bold; color: #ffffff;">${title}</div>
                <div style="color: #000435; font-size: 12px;">${description}</div>
              </div>
            `,
          };
        },
        eventClick: function (info) {
          const { title, extendedProps } = info.event;
          Swal.fire({
            icon: "info",
            title: "Information",
            text: `${title}\n${extendedProps.description}`,
          });
        },
        dateClick: function (info) {
          Swal.fire({
            icon: "info",
            title: "Information",
            text: `The date you clicked: ${info.dateStr}`,
          });
        },
      });

      calendar.render();
    })
    .catch((error) => {
      console.error("Error fetching events:", error);
      Swal.fire({
        icon: "error",
        title: "Error!",
        text: "Gagal mengambil data kalender. Silakan coba lagi nanti.",
      });
    });
});
