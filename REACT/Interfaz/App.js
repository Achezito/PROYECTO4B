import { NavigationContainer } from "@react-navigation/native";
import { createStackNavigator } from "@react-navigation/stack";
import React, { useState } from "react";
import { Button, StyleSheet, Text, TextInput, View } from "react-native";
import Category from './POST/category';
import Material_Components from './POST/material_component';
import Orders from './POST/orders';
import { default as Interfaz, default as InterfazWarehouse } from "./warehouse/interfaz_api"; // Importar interfaz
import SubWarehouseDetails from './warehouse/subwarehousedetails';
import WarehouseDetails from './warehouse/warehousedetails';
import MaterialesScreen from './warehouse/subwarehouseOptions/materialesScreen';
import OrdersScreen from './warehouse/subwarehouseOptions/ordersScreen';
import TransaccionesScreen from './warehouse/subwarehouseOptions/transactionScreen';
import CreateWarehouseScreen from "./warehouse/warehouse_create";
import UpdateWarehouseScreen  from "./warehouse/warehouse_update";
import addSubWareHouse from "./warehouse/subwarehouseOptions/addSubWareHouse";
import UpdateSubWarehouseScreen from "./warehouse/subwarehouseOptions/updateSubWareHouse";
import NuevaTransaccionScreen from "./warehouse/subwarehouseOptions/nuevaTransaccionScreen";
import ProveedoresScreen from "./warehouse/subwarehouseOptions/proveedoresScreen";
import AddProveedorScreen from "./warehouse/subwarehouseOptions/addProveedorScreen";
import EditProveedorScreen from "./warehouse/subwarehouseOptions/editProveedorScreen";
import Suministros from "./warehouse/subwarehouseOptions/suministrosScreen";





function LoginScreen({ navigation }) {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [errorMessage, setErrorMessage] = useState("");

  const handleLogin = () => {
    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    // cambiar el fetch por la direccion donde tiene el login.php
    // Elihu: http://localhost/PROYECTO4B-1/phpfiles/config/login.php
    

    fetch("http://localhost/PROYECTO4B-1/phpfiles/config/login.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          navigation.navigate("Dashboard"); // Navegar al Dashboard si el inicio de sesión es exitoso
        } else {
          navigation.navigate("Dashboard"); // Navegar al Dashboard si el inicio de sesión es exitoso
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
      <Button title="ir a almacenes" onPress={ () => { navigation.navigate('Interfaz'); } } />
      <Button title="ir a los posts" onPress={ () => { navigation.navigate('Post'); } } />
      {errorMessage ? <Text style={styles.error}>{errorMessage}</Text> : null}
    </View>
  );
}

function Post({ navigation }) {
  return (
      <View style={styles.container_POST}>
        <Text style={styles.labelText} onPress={ () => { navigation.navigate("Category"); } }>Categoria</Text>
        <Text style={styles.labelText} onPress={ () => { navigation.navigate("Orders"); } }>Orders</Text>
        <Text style={styles.labelText} onPress={ () => { navigation.navigate("Material_Components"); } }>Material_Components</Text>
     
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
        <Stack.Screen name="Material_Components" component={Material_Components} />
        <Stack.Screen name="InterfazWarehouse" component={InterfazWarehouse} />
        <Stack.Screen name="WarehouseDetails" component={WarehouseDetails} />
        <Stack.Screen name="SubWarehouseDetails" component={SubWarehouseDetails} />
        <Stack.Screen name="MaterialesScreen" component={MaterialesScreen} />
        <Stack.Screen name="TransaccionesScreen" component={TransaccionesScreen} />
        <Stack.Screen name="OrdersScreen" component={OrdersScreen} />
        <Stack.Screen name="CreateWarehouse" component={CreateWarehouseScreen} />
        <Stack.Screen name="UpdateWarehouse" component={UpdateWarehouseScreen} />
        <Stack.Screen name="AddSubWarehouse" component={addSubWareHouse} />
        <Stack.Screen name="UpdateSubWarehouse" component={UpdateSubWarehouseScreen} />
        <Stack.Screen name="NuevaTransaccionScreen" component={NuevaTransaccionScreen} />
        <Stack.Screen name="ProveedoresScreen" component={ProveedoresScreen} />
        <Stack.Screen name="AddProveedorScreen" component={AddProveedorScreen} />
<Stack.Screen name="EditProveedorScreen" component={EditProveedorScreen} />
<Stack.Screen name= "Suministros" component={Suministros} />
  
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
  container_POST: {
    flex: 1,
    padding: 20,
  },
  labelText: {
    backgroundColor: 'black',
    color: 'white', // Asegúrate de que el texto sea visible
    height: 150,
    width: 150,
    borderRadius: 30,
    justifyContent: 'center',
    alignItems: 'center',
    display: 'flex',
    textAlign: 'center',
    lineHeight: 10,
    fontSize: 10,
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