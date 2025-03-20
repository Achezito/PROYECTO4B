import { NavigationContainer } from "@react-navigation/native";
import { createStackNavigator } from "@react-navigation/stack";
import React, { useState } from "react";
import { Button, StyleSheet, Text, TextInput, View } from "react-native";

import Interfaz from "./warehouse/interfaz_api"; // Importar interfaz
import InterfazWarehouse from './warehouse/interfaz_api'; // Ruta correcta al archivo
import WarehouseDetails from './warehouse/warehousedetails'; 
import SubWarehouseDetails from './warehouse/subwarehousedetails'; // Nueva pantalla
import MaterialesSubWarehouse from './warehouse//subwarehouseOptions/materialesScreen'; // Nueva pantalla
import OrdersScreen from "./warehouse/subwarehouseOptions/ordersScreen";
import TransaccionesScreen from "./warehouse/subwarehouseOptions/transactionScreen";



function LoginScreen({ navigation }) {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [errorMessage, setErrorMessage] = useState("");

  const handleLogin = () => {
    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    

    fetch("http://localhost/PROYECTO4B-1/phpfiles/config/login.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          navigation.navigate("Interfaz"); // Navegar al Dashboard si el inicio de sesión es exitoso
        } else {
          navigation.navigate("Interfaz"); // Navegar al Dashboard si el inicio de sesión es exitoso
          setErrorMessage(data.message || "Credenciales incorrectas");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        setErrorMessage("Ocurrió un error al intentar iniciar sesión.");
      });
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Inicio de Sesión</Text>
      <TextInput
        style={styles.input}
        placeholder="Usuario"
        value={username}
        onChangeText={setUsername}
      />
      <TextInput
        style={styles.input}
        placeholder="Contraseña"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
      />
      <Button title="Iniciar Sesión" onPress={handleLogin} />
      <Button title="ir a dashboard" onPress={ () => { navigation.navigate('Interfaz'); } } />
      {errorMessage ? <Text style={styles.error}>{errorMessage}</Text> : null}
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
        <Stack.Screen name="InterfazWarehouse" component={InterfazWarehouse} />
        <Stack.Screen name="WarehouseDetails" component={WarehouseDetails} />
        <Stack.Screen name="MaterialesScreen" component={MaterialesSubWarehouse} />
        <Stack.Screen name="SubWarehouseDetails" component={SubWarehouseDetails} />
        <Stack.Screen name="OrdersScreen" component={OrdersScreen} />
        <Stack.Screen name="TransaccionesScreen" component={TransaccionesScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: "center",
    padding: 16,
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    marginBottom: 16,
    textAlign: "center",
  },
  input: {
    borderWidth: 1,
    borderColor: "#ccc",
    borderRadius: 4,
    padding: 8,
    marginBottom: 16,
  },
  error: {
    color: "red",
    textAlign: "center",
    marginTop: 8,
  },
});