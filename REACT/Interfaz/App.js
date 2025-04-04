import { NavigationContainer } from "@react-navigation/native";
import { createStackNavigator } from "@react-navigation/stack";
import React, { useRef, useState } from "react";
import { BASE_URL } from "./config";
import {
  Button,
  Dimensions,
  StyleSheet,
  Text,
  TextInput,
  View,
} from "react-native";
import Category from "./POST/category";
import Material_Components from "./POST/material_component";
import Orders from "./POST/orders";

import { Animated, ScrollView, TouchableOpacity } from "react-native";
import IconsFont from "react-native-vector-icons/FontAwesome";
import IconDashboard from "react-native-vector-icons/MaterialIcons";
import Dashboard from "./dashboard";
import {
  default as Interfaz,
  default as InterfazWarehouse,
} from "./warehouse/interfaz_api"; // Importar interfaz
import SubWarehouseDetails from "./warehouse/subwarehousedetails";
import AddProveedorScreen from "./warehouse/subwarehouseOptions/addProveedorScreen";
import addSubWareHouse from "./warehouse/subwarehouseOptions/addSubWareHouse";
import AllOrders from "./warehouse/subwarehouseOptions/AllOrdersScreen";
import AllProovedores from "./warehouse/subwarehouseOptions/AllproveedoresScreen";
import AllSuministros from "./warehouse/subwarehouseOptions/AllSuministroScreen";
import AllTransactions from "./warehouse/subwarehouseOptions/AlltransactionScreen";
import EditProveedorScreen from "./warehouse/subwarehouseOptions/editProveedorScreen";
import MaterialesScreen from "./warehouse/subwarehouseOptions/materialesScreen";
import NuevaTransaccionScreen from "./warehouse/subwarehouseOptions/nuevaTransaccionScreen";
import OrdersScreen from "./warehouse/subwarehouseOptions/ordersScreen";
import ProveedoresScreen from "./warehouse/subwarehouseOptions/proveedoresScreen";
import Suministros from "./warehouse/subwarehouseOptions/suministrosScreen";
import TransaccionesScreen from "./warehouse/subwarehouseOptions/transactionScreen";
import UpdateSubWarehouseScreen from "./warehouse/subwarehouseOptions/updateSubWareHouse";
import CreateWarehouseScreen from "./warehouse/warehouse_create";
import UpdateWarehouseScreen from "./warehouse/warehouse_update";
import WarehouseDetails from "./warehouse/warehousedetails";
import RecepcionScreen from "./warehouse/subwarehouseOptions/recepcionScreen";

function LoginScreen({ navigation }) {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [errorMessage, setErrorMessage] = useState("");

  // Función para manejar el login
  const handleLogin = () => {
    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    fetch(`${BASE_URL}/PROYECTO4B-1/phpfiles/config/login.php`, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          navigation.navigate("Dashboard"); // Navegar al Dashboard si el inicio de sesión es exitoso
        } else {
          navigation.navigate("Dashboard");
          setErrorMessage(data.message || "Credenciales incorrectas");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        setErrorMessage("Ocurrió un error al intentar iniciar sesión.");
      });
  };

  const { width } = Dimensions.get("window");
  const [menuOpen, setMenuOpen] = useState(false);
  const translateX = useRef(new Animated.Value(-width)).current;

  const toggleMenu = () => {
    Animated.timing(translateX, {
      toValue: menuOpen ? -width : 0,
      duration: 300,
      useNativeDriver: true,
    }).start();
    setMenuOpen(!menuOpen);
  };

  const sections = [
    { title: "Dashboard", icon: "bar-chart", screen: "Dashboard" },
    { title: "Recepcion", icon: "inbox", screen: "Dashboard" },
    { title: "Proveedores", icon: "local-shipping", screen: "AllProovedores" },
    { title: "Suministros", icon: "inventory", screen: "AllSuministros" },
    { title: "Almacenes", icon: "store", screen: "InterfazWarehouse" },
    { title: "Órdenes", icon: "assignment", screen: "AllOrders" },
    { title: "Reportes", icon: "description", screen: "AllTransactions" },
  ];

  return (
    <View style={styles.loginContainer}>
      <View style={styles.loginCard}>
        <Text style={styles.loginTitle}>Bienvenido</Text>
        <Text style={styles.loginSubtitle}>Inicia sesión para continuar</Text>

        <TextInput
          style={styles.input}
          placeholder="Usuario"
          placeholderTextColor="#FFB74D"
          value={username}
          onChangeText={setUsername}
        />
        <TextInput
          style={styles.input}
          placeholder="Contraseña"
          placeholderTextColor="#FFB74D"
          secureTextEntry
          value={password}
          onChangeText={setPassword}
        />

        {errorMessage ? <Text style={styles.error}>{errorMessage}</Text> : null}

        <TouchableOpacity style={styles.loginButton} onPress={handleLogin}>
          <Text style={styles.loginButtonText}>Iniciar Sesión</Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={styles.dashboardButton}
          onPress={() => navigation.navigate("Dashboard")}
        >
          <Text style={styles.dashboardButtonText}>Ir al Dashboard</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}

function Post({ navigation }) {
  return (
    <View style={styles.container_POST}>
      <Text
        style={styles.labelText}
        onPress={() => {
          navigation.navigate("Category");
        }}
      >
        Categoria
      </Text>
      <Text
        style={styles.labelText}
        onPress={() => {
          navigation.navigate("Orders");
        }}
      >
        Orders
      </Text>
      <Text
        style={styles.labelText}
        onPress={() => {
          navigation.navigate("Material_Components");
        }}
      >
        Material_Components
      </Text>
    </View>
  );
}

const Stack = createStackNavigator();

export default function App() {
  return (
    <NavigationContainer>
      <Stack.Navigator initialRouteName="Login">
        <Stack.Screen name="Login" component={LoginScreen} />
        <Stack.Screen name="Interfaz" component={Interfaz} />
        <Stack.Screen name="Post" component={Post} />
        <Stack.Screen name="Category" component={Category} />
        <Stack.Screen name="Orders" component={Orders} />
        <Stack.Screen
          name="Material_Components"
          component={Material_Components}
        />
        <Stack.Screen name="InterfazWarehouse" component={InterfazWarehouse} />
        <Stack.Screen name="WarehouseDetails" component={WarehouseDetails} />
        <Stack.Screen
          name="SubWarehouseDetails"
          component={SubWarehouseDetails}
        />
        <Stack.Screen name="MaterialesScreen" component={MaterialesScreen} />
        <Stack.Screen
          name="TransaccionesScreen"
          component={TransaccionesScreen}
        />
        <Stack.Screen name="OrdersScreen" component={OrdersScreen} />
        <Stack.Screen
          name="CreateWarehouse"
          component={CreateWarehouseScreen}
        />
        <Stack.Screen
          name="UpdateWarehouse"
          component={UpdateWarehouseScreen}
        />
        <Stack.Screen name="AddSubWarehouse" component={addSubWareHouse} />
        <Stack.Screen
          name="UpdateSubWarehouse"
          component={UpdateSubWarehouseScreen}
        />
        <Stack.Screen
          name="NuevaTransaccionScreen"
          component={NuevaTransaccionScreen}
        />
        <Stack.Screen name="ProveedoresScreen" component={ProveedoresScreen} />
        <Stack.Screen
          name="AddProveedorScreen"
          component={AddProveedorScreen}
        />
        <Stack.Screen
          name="EditProveedorScreen"
          component={EditProveedorScreen}
        />
        <Stack.Screen name="Suministros" component={Suministros} />
        <Stack.Screen name="AllOrders" component={AllOrders} />
        <Stack.Screen name="AllSuministros" component={AllSuministros} />
        <Stack.Screen name="AllProovedores" component={AllProovedores} />
        <Stack.Screen name="Dashboard" component={Dashboard} />
        <Stack.Screen name="AllTransactions" component={AllTransactions} />
        <Stack.Screen name="RecepcionScreen" component={RecepcionScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
}

const styles = StyleSheet.create({
  loginContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#FFF3E0",
    padding: 16,
  },
  loginCard: {
    width: "90%",
    backgroundColor: "#FFFFFF",
    borderRadius: 10,
    padding: 20,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2,
    shadowRadius: 5,
    elevation: 5,
    alignItems: "center",
  },
  loginTitle: {
    fontSize: 28,
    fontWeight: "bold",
    color: "#FF6F00",
    marginBottom: 10,
  },
  loginSubtitle: {
    fontSize: 16,
    color: "#FF8A65",
    marginBottom: 20,
  },
  input: {
    width: "100%",
    borderWidth: 1,
    borderColor: "#FFCC80",
    borderRadius: 5,
    padding: 10,
    marginBottom: 16,
    backgroundColor: "#FFF8E1",
    color: "#FF6F00",
  },
  error: {
    color: "#D32F2F",
    textAlign: "center",
    marginBottom: 10,
  },
  loginButton: {
    width: "100%",
    backgroundColor: "#FF6F00",
    padding: 15,
    borderRadius: 5,
    alignItems: "center",
    marginBottom: 10,
  },
  loginButtonText: {
    color: "#FFFFFF",
    fontSize: 16,
    fontWeight: "bold",
  },
  registerButton: {
    marginTop: 10,
  },
  registerButtonText: {
    color: "#FF6F00",
    fontSize: 14,
    textDecorationLine: "underline",
  },
});
