from graphviz import Digraph
from IPython.display import Image, display

# Recreate the Class Diagram for TravelWizard
class_diagram = Digraph('ClassDiagram', format='png')
class_diagram.attr(size='10,10', rankdir='TB')  # Set size and top-to-bottom layout
class_diagram.attr(splines='ortho')  # Orthogonal edges for clarity
class_diagram.attr(concentrate='true')  # Combine common edges when possible

# Define classes with attributes and methods
classes = {
    "User": [
        "- userID: int", "- name: String", "- email: String", "- password: String",
        "+ register()", "+ login()", "+ updateProfile()"
    ],
    "Booking": [
        "- bookingID: int", "- userID: int", "- packageID: int", "- bookingDate: Date",
        "+ confirmBooking()", "+ cancelBooking()", "+ viewBookingDetails()"
    ],
    "TravelPackage": [
        "- packageID: int", "- destination: String", "- flightDetails: String",
        "- accommodationDetails: String", "- itinerary: String", "+ getPackageDetails()"
    ],
    "Payment": [
        "- paymentID: int", "- bookingID: int", "- amount: double",
        "- paymentStatus: String", "+ processPayment()", "+ refundPayment()"
    ],
    "Admin": [
        "- adminID: int", "- name: String", "- email: String",
        "+ manageUsers()", "+ manageBookings()", "+ managePackages()"
    ]
}

# Add nodes for classes
for class_name, attributes in classes.items():
    label = f"{class_name}\n" + "\n".join(attributes)
    class_diagram.node(class_name, label=label, shape="rectangle", style="filled", color="lightblue")

# Define relationships (edges)
relationships = [
    ("User", "Booking", "makes"),
    ("Booking", "TravelPackage", "refers to"),
    ("Booking", "Payment", "triggers"),
    ("Admin", "User", "manages"),
    ("Admin", "Booking", "manages"),
    ("Admin", "TravelPackage", "manages")
]

for src, dest, label in relationships:
    class_diagram.edge(src, dest, label=label)

# Save and render the diagram
diagram_path = "/mnt/data/TravelWizard.png"
class_diagram.render(diagram_path, format='png', cleanup=False)

# Display the generated diagram
display(Image(filename=diagram_path))
print(f"Class diagram saved at: {diagram_path}")
