import React from "react";
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
} from "react-native";
import { MaterialIcons, FontAwesome5, Entypo } from "@expo/vector-icons";

export default function SubWarehouseDetails({ route, navigation }) {
  const { id, location } = route.params; // Recibe los parámetros de navegación

  const handleNavigation = (option) => {
    if (option === "Materiales") {
      navigation.navigate("MaterialesScreen", { id });
    } else if (option === "Órdenes") {
      navigation.navigate("OrdersScreen", { id });
    } else if (option === "Transacciones") {
      navigation.navigate("TransaccionesScreen", { id });
    } else if (option === "NuevaTransaccion") {
      navigation.navigate("NuevaTransaccionScreen", { id });
    } else if (option === "Proveedores") {
      navigation.navigate("ProveedoresScreen", { id });
    } else if (option === "Suministros") {
      navigation.navigate("Suministros", { id });
    } else if (option === "AllOrders") {
      navigation.navigate("AllOrders", { id });
    } else if (option === "AllSuministros") {
      navigation.navigate("AllSuministros", { id });
    } else if (option === "RecepcionScreen") {
      navigation.navigate("RecepcionScreen", { id });
    } else if (option === "asignarScreen") {
      navigation.navigate("asignarScreen", { id });
    } else {
      console.log("Opción no válida");
    }
  };

  return (
    <View style={styles.container}>
      {/* Barra de navegación superior */}
      <View style={styles.navbar}>
        <Text style={styles.navbarTitle}>Subalmacén #{id}</Text>
      </View>

      {/* Contenido principal */}
      <ScrollView contentContainerStyle={styles.content}>
        <Text style={styles.title}>Detalles del Subalmacén</Text>
        <Text style={styles.text}>ID: {id}</Text>
        <Text style={styles.text}>Ubicación: {location}</Text>

        {/* Opciones del menú */}
        <View style={styles.menuContainer}>
          <TouchableOpacity
            style={styles.menuButton}
            onPress={() => handleNavigation("Órdenes")}
          >
            <FontAwesome5 name="clipboard-list" size={24} color="white" />
            <Text style={styles.menuText}>Órdenes</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuButton}
            onPress={() => handleNavigation("AllOrders")}
          >
            <FontAwesome5 name="clipboard-list" size={24} color="white" />
            <Text style={styles.menuText}>Ver ordenes</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuButton}
            onPress={() => handleNavigation("Suministros")}
          >
            <MaterialIcons name="storage" size={24} color="white" />
            <Text style={styles.menuText}>Suministros</Text>
          </TouchableOpacity>
          <TouchableOpacity
            style={styles.menuButton}
            onPress={() => handleNavigation("AllSuministros")}
          >
            <MaterialIcons name="storage" size={24} color="white" />
            <Text style={styles.menuText}>Ver suministros</Text>
          </TouchableOpacity>
          <TouchableOpacity
            style={styles.menuButton}
            onPress={() => handleNavigation("RecepcionScreen")}
          >
            <MaterialIcons name="storage" size={24} color="white" />
            <Text style={styles.menuText}>Recibir materiales</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuButton}
            onPress={() => handleNavigation("asignarScreen")}
          >
            <MaterialIcons name="storage" size={24} color="white" />
            <Text style={styles.menuText}>Asignar</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuButton}
            onPress={() => handleNavigation("Materiales")}
          >
            <MaterialIcons name="inventory" size={24} color="white" />
            <Text style={styles.menuText}>Materiales</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuButton}
            onPress={() => handleNavigation("Transacciones")}
          >
            <MaterialIcons name="swap-horiz" size={24} color="white" />
            <Text style={styles.menuText}>Transacciones</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuButton}
            onPress={() => handleNavigation("NuevaTransaccion")}
          >
            <MaterialIcons name="add-circle-outline" size={24} color="white" />
            <Text style={styles.menuText}>Nueva Transacción</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuButton}
            onPress={() => handleNavigation("Proveedores")}
          >
            <Entypo name="users" size={24} color="white" />
            <Text style={styles.menuText}>Proveedores</Text>
          </TouchableOpacity>
        </View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#FFF3E0", // Fondo naranja claro
  },
  navbar: {
    backgroundColor: "#FF9800", // Naranja para la barra de navegación
    paddingVertical: 15,
    paddingHorizontal: 20,
    alignItems: "center",
    justifyContent: "center",
  },
  navbarTitle: {
    fontSize: 20,
    fontWeight: "bold",
    color: "white",
  },
  content: {
    padding: 20,
    alignItems: "center",
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    color: "#E65100", // Naranja oscuro
    marginBottom: 16,
  },
  text: {
    fontSize: 18,
    color: "#BF360C", // Naranja más oscuro
    marginBottom: 8,
  },
  menuContainer: {
    marginTop: 20,
    width: "100%",
    alignItems: "center",
  },
  menuButton: {
    flexDirection: "row", // Ícono y texto en línea
    alignItems: "center",
    backgroundColor: "#FF9800", // Naranja para los botones
    paddingVertical: 15,
    paddingHorizontal: 20,
    borderRadius: 10,
    marginBottom: 15,
    width: "90%", // Botones más anchos
    justifyContent: "space-between",
  },
  menuText: {
    fontSize: 18,
    color: "white",
    fontWeight: "bold",
  },
});
